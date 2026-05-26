<?php

namespace App\Libraries;

use App\Models\ArtistModel;

/**
 * ArtistLibrary
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
    }

    /**
     * Vrátí všechny umělce (bez smazaných).
     *
     * @return array Seznam umělců
     */
    public function getAll(): array
    {
        return $this->model->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Vrátí jednoho umělce podle ID.
     *
     * @param int $id ID umělce
     * @return array|null Data umělce nebo null pokud neexistuje
     */
    public function getById(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * Uloží nového umělce včetně nahrání fotografie.
     *
     * @param array $data    POST data z formuláře
     * @param mixed $file    Nahraný soubor (instance UploadedFile nebo null)
     * @return bool          True při úspěchu, false při chybě
     */
    public function create(array $data, $file = null): bool
    {
        if ($file !== null && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($this->uploadPath, $newName);
            $data['photo'] = base_url($this->uploadUrl . $newName);
        }

        return $this->model->insert($data) !== false;
    }

    /**
     * Aktualizuje existujícího umělce.
     * Pokud je nahrán nový obrázek, starý se smaže.
     *
     * @param int   $id   ID umělce
     * @param array $data POST data z formuláře
     * @param mixed $file Nahraný soubor nebo null
     * @return bool       True při úspěchu, false při chybě
     */
    public function update(int $id, array $data, $file = null): bool
    {
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

        return $this->model->update($id, $data);
    }

    /**
     * Provede soft delete umělce (nastaví deleted_at).
     *
     * @param int $id ID umělce
     * @return bool   True při úspěchu, false při chybě
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * Vrátí chyby validace z modelu.
     *
     * @return array Pole chybových zpráv
     */
    public function getErrors(): array
    {
        return $this->model->errors();
    }
}