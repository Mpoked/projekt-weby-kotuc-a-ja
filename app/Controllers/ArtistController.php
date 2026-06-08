<?php

namespace App\Controllers;

use App\Libraries\ArtistLib;
use App\Models\GenreModel;

/**
 * ArtistController
 *
 * Stará se o zobrazení, přidávání, editaci a mazání umělců.
 * Business logika je delegována do ArtistLib.
 */
class ArtistController extends BaseController
{
    protected ArtistLib $artistLib;
    protected GenreModel $genreModel;

    public function __construct()
    {
        $this->artistLib  = new ArtistLib();
        $this->genreModel = new GenreModel();
    }

    /**
     * Zobrazí seznam všech umělců v tabulce.
     */
    public function index(): string
    {
        return view('artist/index', [
            'title'       => 'Umělci',
            'artist_list' => $this->artistLib->getAll(),
        ]);
    }

    /**
     * Zobrazí detail jednoho umělce včetně jeho alb a žánrů.
     *
     * @param int $id  ID umělce
     */
    public function show(int $id): string
    {
        $artist = $this->artistLib->getById($id);
        if (! $artist) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Umělec #$id nenalezen.");
        }

        $db = \Config\Database::connect();
        $genres = $db->table('genre')
            ->select('genre.id, genre.name', false)
            ->join('artist_genre', 'artist_genre.genre_id = genre.id')
            ->where('artist_genre.artist_id', $id)
            ->where('genre.deleted_at IS NULL')
            ->get()->getResultObject();

        $albums = (new \App\Models\AlbumModel())
            ->where('artist_id', $id)
            ->orderBy('release_date', 'DESC')
            ->findAll();

        return view('artist/show', [
            'title'  => $artist->name,
            'artist' => $artist,
            'genres' => $genres,
            'albums' => $albums,
        ]);
    }

    /**
     * Zobrazí formulář pro přidání nového umělce.
     * Načte seznam žánrů z DB pro Select2 multiselect.
     */
    public function create(): string
    {
        return view('artist/create', [
            'title'         => 'Přidat umělce',
            'genre_options' => $this->genreModel->orderBy('name')->findAll(),
        ]);
    }

    /**
     * Zpracuje POST data z formuláře a uloží nového umělce včetně žánrů.
     * Po úspěchu / neúspěchu nastaví flash zprávu a přesměruje.
     */
    public function store()
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'bio'         => $this->request->getPost('bio'),
            'country'     => $this->request->getPost('country'),
            'formed_year' => $this->request->getPost('formed_year') ?: null,
            'genres'      => $this->request->getPost('genres') ?? [],
        ];

        $file = $this->request->getFile('photo');

        if ($this->artistLib->create($data, $file)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Umělec byl úspěšně přidán.']);
            return redirect()->to(base_url('artist'));
        }

        session()->setFlashdata('errors', $this->artistLib->getErrors());
        return redirect()->back()->withInput();
    }

    /**
     * Zobrazí formulář pro editaci existujícího umělce.
     * Předvyplní aktuálně přiřazené žánry pro Select2.
     *
     * @param int $id  ID umělce
     */
    public function edit(int $id): string
    {
        $artist = $this->artistLib->getById($id);
        if (! $artist) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Umělec #$id nenalezen.");
        }

        return view('artist/edit', [
            'title'            => 'Upravit umělce',
            'artist'           => $artist,
            'genre_options'    => $this->genreModel->orderBy('name')->findAll(),
            'selected_genres'  => $this->artistLib->getGenreIds($id),
        ]);
    }

    /**
     * Zpracuje POST data z editačního formuláře a aktualizuje umělce.
     *
     * @param int $id  ID umělce
     */
    public function update(int $id)
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'bio'         => $this->request->getPost('bio'),
            'country'     => $this->request->getPost('country'),
            'formed_year' => $this->request->getPost('formed_year') ?: null,
            'genres'      => $this->request->getPost('genres') ?? [],
        ];

        $file = $this->request->getFile('photo');

        if ($this->artistLib->update($id, $data, $file)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Umělec byl úspěšně upraven.']);
            return redirect()->to(base_url('artist'));
        }

        session()->setFlashdata('errors', $this->artistLib->getErrors());
        return redirect()->back()->withInput();
    }

    /**
     * Provede soft delete umělce (nastaví deleted_at).
     * Volá se přes POST z modálního okna potvrzení.
     *
     * @param int $id  ID umělce
     */
    public function delete(int $id)
    {
        if ($this->artistLib->delete($id)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Umělec byl smazán.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se smazat umělce.']);
        }

        return redirect()->to(base_url('artist'));
    }
}
