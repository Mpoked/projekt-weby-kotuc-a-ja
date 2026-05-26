<?php

namespace App\Controllers;

use App\Libraries\ArtistLib;

/**
 * ArtistController
 *
 * Stará se o zobrazení, přidávání, editaci a mazání umělců.
 * Business logika je delegována do ArtistLib.
 */
class ArtistController extends BaseController
{
    protected ArtistLib $artistLib;

    public function __construct()
    {
        $this->artistLib = new ArtistLib();
    }

    /**
     * Zobrazí seznam všech umělců.
     */
    public function index(): string
    {
        $data = [
            'title'       => 'Umělci',
            'artist_list' => $this->artistLib->getAll(),
        ];

        return view('artist/index', $data);
    }

    /**
     * Zobrazí formulář pro přidání nového umělce.
     */
    public function create(): string
    {
        return view('artist/create', ['title' => 'Přidat umělce']);
    }

    /**
     * Zpracuje POST data z formuláře a uloží nového umělce.
     * Po úspěchu / neúspěchu nastaví flash zprávu a přesměruje.
     */
    public function store()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'bio'  => $this->request->getPost('bio'),
        ];

        $file = $this->request->getFile('photo');

        if ($this->artistLib->create($data, $file)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Umělec byl úspěšně přidán.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se přidat umělce.']);
            session()->setFlashdata('errors', $this->artistLib->getErrors());
            return redirect()->back()->withInput();
        }

        return redirect()->to('/artist');
    }

    /**
     * Zobrazí formulář pro editaci existujícího umělce.
     *
     * @param int $id ID umělce
     */
    public function edit(int $id): string
    {
        $artist = $this->artistLib->getById($id);

        if (! $artist) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Umělec #$id nenalezen.");
        }

        return view('artist/edit', [
            'title'  => 'Upravit umělce',
            'artist' => $artist,
        ]);
    }

    /**
     * Zpracuje POST data z editačního formuláře a aktualizuje umělce.
     *
     * @param int $id ID umělce
     */
    public function update(int $id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'bio'  => $this->request->getPost('bio'),
        ];

        $file = $this->request->getFile('photo');

        if ($this->artistLib->update($id, $data, $file)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Umělec byl úspěšně upraven.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se upravit umělce.']);
            session()->setFlashdata('errors', $this->artistLib->getErrors());
            return redirect()->back()->withInput();
        }

        return redirect()->to('/artist');
    }

    /**
     * Provede soft delete umělce.
     * Volá se přes POST z modálního okna.
     *
     * @param int $id ID umělce
     */
    public function delete(int $id)
    {
        if ($this->artistLib->delete($id)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Umělec byl smazán.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se smazat umělce.']);
        }

        return redirect()->to('/artist');
    }
}