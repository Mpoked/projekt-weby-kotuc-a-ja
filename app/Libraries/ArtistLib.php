<?php

namespace App\Libraries;

use App\Models\ArtistModel;

/**
 * ArtistLib
 *
 * Knihovna pro opakující se operace s umělci.
 * Odděluje business logiku od controlleru.
 */
class ArtistLib
{
    protected ArtistModel $model;
    protected string $uploadPath = WRITEPATH . 'uploads/artists/';
    protected string $uploadUrl  = 'uploads/artists/';

    public function __construct()
    {
        $this->model = new ArtistModel();
        if (! is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0775, true);
        }
    }

    /**
     * Vrátí všechny umělce seřazené podle jména (bez smazaných).
     *
     * @return array Seznam umělců z tabulky artist
     */
    public function getAll(): array
    {
        return $this->model->orderBy('id', 'ASC')->findAll();
    }

    /**
     * Vrátí jednoho umělce podle jeho ID.
     *
     * @param int $id  ID umělce v tabulce artist
     * @return array|null  Data umělce, nebo null pokud neexistuje / byl smazán
     */
    public function getById(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * Uloží nového umělce do DB včetně volitelného nahrání fotografie.
     * Po vložení synchronizuje žánry přes vazební tabulku artist_genre.
     *
     * @param array $data   POST data z formuláře (name, bio, country, formed_year...)
     * @param mixed $file   Instance UploadedFile nebo null (nepovinné)
     * @return bool         True při úspěchu, false při validační chybě
     */
    public function create(array $data, $file = null): bool
    {
        $genreIds = $data['genres'] ?? [];
        unset($data['genres']);

        if ($file !== null && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($this->uploadPath, $newName);
            $data['photo'] = base_url($this->uploadUrl . $newName);
        }

        $insertId = $this->model->insert($data, true);
        if ($insertId === false) {
            return false;
        }

        $this->syncGenres((int) $insertId, (array) $genreIds);
        return true;
    }

    /**
     * Aktualizuje existujícího umělce.
     * Pokud je nahrán nový obrázek, starý soubor se smaže.
     * Po aktualizaci synchronizuje žánry.
     *
     * @param int   $id    ID umělce
     * @param array $data  POST data z formuláře
     * @param mixed $file  Instance UploadedFile nebo null
     * @return bool        True při úspěchu, false při chybě
     */
    public function update(int $id, array $data, $file = null): bool
    {
        $genreIds = $data['genres'] ?? [];
        unset($data['genres']);

        if ($file !== null && $file->isValid() && ! $file->hasMoved()) {
            $artist = $this->getById($id);
            if ($artist && $artist['photo']) {
                $oldFile = $this->uploadPath . basename($artist['photo']);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $newName = $file->getRandomName();
            $file->move($this->uploadPath, $newName);
            $data['photo'] = base_url($this->uploadUrl . $newName);
        }

        if (! $this->model->update($id, $data)) {
            return false;
        }

        $this->syncGenres($id, (array) $genreIds);
        return true;
    }

    /**
     * Provede soft delete umělce (nastaví deleted_at na aktuální čas).
     *
     * @param int $id  ID umělce
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

    /**
     * Synchronizuje žánry umělce ve vazební tabulce artist_genre.
     * Nejprve smaže stávající záznamy, poté vloží nové.
     *
     * @param int   $artistId  ID umělce
     * @param array $genreIds  Pole ID žánrů (může být prázdné)
     * @return void
     */
    public function syncGenres(int $artistId, array $genreIds): void
    {
        $db = \Config\Database::connect();
        $db->table('artist_genre')->where('artist_id', $artistId)->delete();

        foreach ($genreIds as $gid) {
            $gid = (int) $gid;
            if ($gid > 0) {
                $db->table('artist_genre')->insert([
                    'artist_id' => $artistId,
                    'genre_id'  => $gid,
                ]);
            }
        }
    }

    /**
     * Vrátí pole ID žánrů přiřazených danému umělci.
     *
     * @param int $artistId  ID umělce
     * @return array         Pole celých čísel (genre_id)
     */
    public function getGenreIds(int $artistId): array
    {
        $db = \Config\Database::connect();
        $rows = $db->table('artist_genre')
            ->select('genre_id')
            ->where('artist_id', $artistId)
            ->get()
            ->getResultArray();

        return array_column($rows, 'genre_id');
    }
}
