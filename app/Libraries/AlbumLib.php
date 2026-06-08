<?php

namespace App\Libraries;

use App\Models\AlbumModel;

/**
 * AlbumLib
 *
 * Knihovna pro operace s alby.
 * Odděluje business logiku od controlleru – upload obálky, JOINy, filtrace.
 */
class AlbumLib
{
    protected AlbumModel $model;
    protected string $uploadPath = WRITEPATH . 'uploads/albums/';
    protected string $uploadUrl  = 'uploads/albums/';

    public function __construct()
    {
        $this->model = new AlbumModel();
        if (! is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0775, true);
        }
    }

    /**
     * Vrátí stránkovaný seznam alb s JOIN na umělce.
     * Podporuje filtrování podle žánru a roku vydání.
     *
     * @param array $filters     Asociativní pole: 'genre_id' (int), 'year' (int)
     * @param int   $perPage     Počet záznamů na stránku
     * @return array             Pole ['albums' => [...], 'pager' => Pager]
     */
    public function getPaginated(array $filters = [], int $perPage = 12): array
    {
        $db = \Config\Database::connect();

        $builder = $db->table('album')
            ->select('album.*, artist.name AS artist_name')
            ->join('artist', 'artist.id = album.artist_id')
            ->whereNull('album.deleted_at')
            ->orderBy('album.release_date', 'DESC');

        if (! empty($filters['year'])) {
            $builder->where('YEAR(album.release_date)', (int) $filters['year']);
        }

        if (! empty($filters['genre_id'])) {
            $builder->join('artist_genre', 'artist_genre.artist_id = album.artist_id')
                    ->where('artist_genre.genre_id', (int) $filters['genre_id']);
        }

        $totalRows = $builder->countAllResults(false);
        $page      = (int) ($_GET['page'] ?? 1);
        $offset    = ($page - 1) * $perPage;
        $albums    = $builder->limit($perPage, $offset)->get()->getResultArray();

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $totalRows);

        return ['albums' => $albums, 'pager' => $pager, 'total' => $totalRows, 'perPage' => $perPage, 'page' => $page];
    }

    /**
     * Vrátí seznam všech alb bez stránkování (pro dropdowny).
     *
     * @return array  Pole alb s artist_name
     */
    public function getAll(): array
    {
        $db = \Config\Database::connect();
        return $db->table('album')
            ->select('album.*, artist.name AS artist_name')
            ->join('artist', 'artist.id = album.artist_id')
            ->whereNull('album.deleted_at')
            ->orderBy('album.release_date', 'DESC')
            ->get()->getResultArray();
    }

    /**
     * Vrátí základní data jednoho alba podle ID.
     *
     * @param int $id  ID alba
     * @return array|null  Data alba, nebo null pokud neexistuje
     */
    public function getById(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * Vrátí detail alba včetně jména umělce, tracků, recenzí a průměrného hodnocení.
     * Používá JOIN a agregační funkci AVG().
     *
     * @param int $id  ID alba
     * @return array|null  Pole s klíči: album data, artist_name, avg_rating, tracks, reviews
     */
    public function getWithDetails(int $id): ?array
    {
        $db = \Config\Database::connect();

        $album = $db->table('album')
            ->select('album.*, artist.name AS artist_name,
                      AVG(review.rating) AS avg_rating,
                      COUNT(DISTINCT review.id) AS review_count')
            ->join('artist', 'artist.id = album.artist_id')
            ->join('review', 'review.album_id = album.id AND review.deleted_at IS NULL', 'left')
            ->where('album.id', $id)
            ->whereNull('album.deleted_at')
            ->groupBy('album.id')
            ->get()->getRowArray();

        if (! $album) {
            return null;
        }

        $album['tracks'] = $db->table('track')
            ->where('album_id', $id)
            ->whereNull('deleted_at')
            ->orderBy('track_number', 'ASC')
            ->get()->getResultArray();

        $album['reviews'] = $db->table('review')
            ->select('review.*, user.username')
            ->join('user', 'user.id = review.user_id')
            ->where('review.album_id', $id)
            ->whereNull('review.deleted_at')
            ->orderBy('review.created_at', 'DESC')
            ->get()->getResultArray();

        return $album;
    }

    /**
     * Uloží nové album do DB včetně volitelného nahrání obálky.
     *
     * @param array $data   POST data (title, artist_id, release_date, description, label)
     * @param mixed $file   Instance UploadedFile nebo null
     * @return bool         True při úspěchu, false při chybě validace
     */
    public function create(array $data, $file = null): bool
    {
        if ($file !== null && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($this->uploadPath, $newName);
            $data['cover_image'] = base_url($this->uploadUrl . $newName);
        }

        return $this->model->insert($data) !== false;
    }

    /**
     * Aktualizuje existující album.
     * Pokud je nahrán nový obrázek, starý soubor se smaže.
     *
     * @param int   $id    ID alba
     * @param array $data  POST data z formuláře
     * @param mixed $file  Instance UploadedFile nebo null
     * @return bool        True při úspěchu, false při chybě
     */
    public function update(int $id, array $data, $file = null): bool
    {
        if ($file !== null && $file->isValid() && ! $file->hasMoved()) {
            $album = $this->getById($id);
            if ($album && $album['cover_image']) {
                $oldFile = $this->uploadPath . basename($album['cover_image']);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $newName = $file->getRandomName();
            $file->move($this->uploadPath, $newName);
            $data['cover_image'] = base_url($this->uploadUrl . $newName);
        }

        return $this->model->update($id, $data);
    }

    /**
     * Provede soft delete alba (nastaví deleted_at na aktuální čas).
     *
     * @param int $id  ID alba
     * @return bool    True při úspěchu, false při chybě
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * Vrátí chyby validace z modelu po neúspěšném insert/update.
     *
     * @return array  Asociativní pole [pole => chybová zpráva]
     */
    public function getErrors(): array
    {
        return $this->model->errors();
    }
}
