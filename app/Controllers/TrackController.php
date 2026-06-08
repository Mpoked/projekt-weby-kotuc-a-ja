<?php

namespace App\Controllers;

use App\Models\TrackModel;
use App\Models\AlbumModel;

/**
 * TrackController
 *
 * Spravuje skladby (tracky) nested pod albem.
 * Všechny operace jsou pouze pro admina a po dokončení přesměrovávají na detail alba.
 */
class TrackController extends BaseController
{
    protected TrackModel $trackModel;
    protected AlbumModel $albumModel;

    public function __construct()
    {
        $this->trackModel = new TrackModel();
        $this->albumModel = new AlbumModel();
    }

    /**
     * Zobrazí formulář pro přidání nové skladby k albu.
     *
     * @param int $albumId  ID nadřazeného alba
     */
    public function create(int $albumId): string
    {
        $album = $this->albumModel->find($albumId);
        if (! $album) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Album #$albumId nenalezeno.");
        }

        return view('track/create', [
            'title'   => 'Přidat skladbu',
            'album'   => $album,
        ]);
    }

    /**
     * Uloží novou skladbu do DB a přesměruje na detail alba.
     *
     * @param int $albumId  ID nadřazeného alba
     */
    public function store(int $albumId)
    {
        $data = [
            'album_id'     => $albumId,
            'title'        => $this->request->getPost('title'),
            'track_number' => $this->request->getPost('track_number') ?: null,
            'duration'     => $this->request->getPost('duration') ?: null,
        ];

        if ($this->trackModel->insert($data) !== false) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Skladba byla přidána.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se přidat skladbu.']);
        }

        return redirect()->to(base_url('album/' . $albumId));
    }

    /**
     * Zobrazí formulář pro editaci existující skladby.
     *
     * @param int $albumId  ID nadřazeného alba
     * @param int $id       ID skladby
     */
    public function edit(int $albumId, int $id): string
    {
        $track = $this->trackModel->find($id);
        $album = $this->albumModel->find($albumId);

        if (! $track || ! $album) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Skladba nebo album nenalezeny.");
        }

        return view('track/edit', [
            'title' => 'Upravit skladbu',
            'track' => $track,
            'album' => $album,
        ]);
    }

    /**
     * Uloží změny skladby a přesměruje na detail alba.
     *
     * @param int $albumId  ID nadřazeného alba
     * @param int $id       ID skladby
     */
    public function update(int $albumId, int $id)
    {
        $data = [
            'title'        => $this->request->getPost('title'),
            'track_number' => $this->request->getPost('track_number') ?: null,
            'duration'     => $this->request->getPost('duration') ?: null,
        ];

        if ($this->trackModel->update($id, $data)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Skladba byla upravena.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se upravit skladbu.']);
        }

        return redirect()->to(base_url('album/' . $albumId));
    }

    /**
     * Provede soft delete skladby a přesměruje na detail alba.
     *
     * @param int $albumId  ID nadřazeného alba
     * @param int $id       ID skladby
     */
    public function delete(int $albumId, int $id)
    {
        if ($this->trackModel->delete($id)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Skladba byla smazána.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se smazat skladbu.']);
        }

        return redirect()->to(base_url('album/' . $albumId));
    }
}
