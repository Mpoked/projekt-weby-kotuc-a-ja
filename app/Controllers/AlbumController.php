<?php

namespace App\Controllers;

use App\Libraries\AlbumLib;
use App\Models\ArtistModel;

/**
 * AlbumController
 *
 * Stará se o zobrazení, přidávání, editaci, mazání alb a generování PDF.
 * Business logika (upload, JOIN, AVG) je v AlbumLib.
 */
class AlbumController extends BaseController
{
    protected AlbumLib    $albumLib;
    protected ArtistModel $artistModel;

    public function __construct()
    {
        $this->albumLib    = new AlbumLib();
        $this->artistModel = new ArtistModel();
    }

    /**
     * Zobrazí stránkovaný seznam alb jako karty.
     * Podporuje GET filtry: genre_id, year.
     * Počet na stránku se načítá z konfigurace MusicArchive.
     */
    public function index(): string
    {
        $filters = [
            'genre_id' => $this->request->getGet('genre_id'),
            'year'     => $this->request->getGet('year'),
        ];
        $perPage = (int) config('MusicArchive')->itemsPerPage;
        $result  = $this->albumLib->getPaginated($filters, $perPage);

        $db = \Config\Database::connect();
        $genres = $db->table('genre')->where('deleted_at IS NULL')->orderBy('name')->get()->getResultArray();

        $years = $db->table('album')
            ->select('YEAR(release_date) AS yr')
            ->where('deleted_at IS NULL')
            ->where('release_date IS NOT NULL')
            ->groupBy('yr')
            ->orderBy('yr', 'DESC')
            ->get()->getResultArray();

        return view('album/index', [
            'title'   => 'Alba',
            'albums'  => $result['albums'],
            'pager'   => $result['pager'],
            'total'   => $result['total'],
            'perPage' => $result['perPage'],
            'page'    => $result['page'],
            'filters' => $filters,
            'genres'  => $genres,
            'years'   => array_column($years, 'yr'),
        ]);
    }

    /**
     * Zobrazí detail alba: informace, tracklist, recenze a průměrné hodnocení.
     * Používá JOIN (artist) a agregační funkci AVG(rating) přes AlbumLib.
     *
     * @param int $id  ID alba
     */
    public function show(int $id): string
    {
        $album = $this->albumLib->getWithDetails($id);
        if (! $album) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Album #$id nenalezeno.");
        }

        $userReviewExists = false;
        if (session()->get('logged_in')) {
            $db = \Config\Database::connect();
            $userReviewExists = (bool) $db->table('review')
                ->where('album_id', $id)
                ->where('user_id', session()->get('user_id'))
                ->where('deleted_at IS NULL')
                ->countAllResults();
        }

        return view('album/show', [
            'title'            => $album['title'],
            'album'            => $album,
            'userReviewExists' => $userReviewExists,
        ]);
    }

    /**
     * Zobrazí formulář pro přidání nového alba.
     * Načte seznam umělců z DB pro dropdown.
     */
    public function create(): string
    {
        return view('album/create', [
            'title'          => 'Přidat album',
            'artist_options' => $this->artistModel->orderBy('name')->findAll(),
        ]);
    }

    /**
     * Zpracuje POST data a uloží nové album.
     * Po úspěchu nastaví flash zprávu a přesměruje na seznam alb.
     */
    public function store()
    {
        $data = [
            'artist_id'    => $this->request->getPost('artist_id'),
            'title'        => $this->request->getPost('title'),
            'release_date' => $this->request->getPost('release_date') ?: null,
            'label'        => $this->request->getPost('label'),
            'description'  => $this->request->getPost('description'),
        ];

        $file = $this->request->getFile('cover_image');

        if ($this->albumLib->create($data, $file)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Album bylo úspěšně přidáno.']);
            return redirect()->to(base_url('album'));
        }

        session()->setFlashdata('errors', $this->albumLib->getErrors());
        return redirect()->back()->withInput();
    }

    /**
     * Zobrazí formulář pro editaci alba.
     * Předvyplní aktuální data a načte seznam umělců pro dropdown.
     *
     * @param int $id  ID alba
     */
    public function edit(int $id): string
    {
        $album = $this->albumLib->getById($id);
        if (! $album) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Album #$id nenalezeno.");
        }

        return view('album/edit', [
            'title'          => 'Upravit album',
            'album'          => $album,
            'artist_options' => $this->artistModel->orderBy('name')->findAll(),
        ]);
    }

    /**
     * Zpracuje POST data z editačního formuláře a aktualizuje album.
     *
     * @param int $id  ID alba
     */
    public function update(int $id)
    {
        $data = [
            'artist_id'    => $this->request->getPost('artist_id'),
            'title'        => $this->request->getPost('title'),
            'release_date' => $this->request->getPost('release_date') ?: null,
            'label'        => $this->request->getPost('label'),
            'description'  => $this->request->getPost('description'),
        ];

        $file = $this->request->getFile('cover_image');

        if ($this->albumLib->update($id, $data, $file)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Album bylo úspěšně upraveno.']);
            return redirect()->to(base_url('album/' . $id));
        }

        session()->setFlashdata('errors', $this->albumLib->getErrors());
        return redirect()->back()->withInput();
    }

    /**
     * Provede soft delete alba (nastaví deleted_at).
     * Volá se přes POST z modálního okna potvrzení.
     *
     * @param int $id  ID alba
     */
    public function delete(int $id)
    {
        if ($this->albumLib->delete($id)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Album bylo smazáno.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se smazat album.']);
        }

        return redirect()->to(base_url('album'));
    }

    /**
     * Vygeneruje PDF soubor s informacemi o albu (obálka, tracklist, recenze).
     * Ke stažení pomocí knihovny Dompdf.
     *
     * @param int $id  ID alba
     */
    public function pdf(int $id)
    {
        $album = $this->albumLib->getWithDetails($id);
        if (! $album) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Album #$id nenalezeno.");
        }

        $html = view('album/pdf', ['album' => $album]);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'album-' . preg_replace('/[^a-z0-9]/i', '-', $album['title']) . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }
}
