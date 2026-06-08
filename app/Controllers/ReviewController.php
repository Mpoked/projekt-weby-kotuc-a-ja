<?php

namespace App\Controllers;

use App\Models\ReviewModel;

/**
 * ReviewController
 *
 * Spravuje recenze alb. Přidat recenzi může každý přihlášený uživatel (max. 1 na album),
 * smazat může pouze admin. Zobrazení je na stránce alba (AlbumController::show).
 */
class ReviewController extends BaseController
{
    protected ReviewModel $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    /**
     * Uloží novou recenzi pro dané album.
     * Kontroluje, zda uživatel ještě nenapsal recenzi pro toto album (UNIQUE).
     *
     * @param int $albumId  ID alba, ke kterému se recenze přidává
     */
    public function store(int $albumId)
    {
        $userId = session()->get('user_id');

        $existing = $this->reviewModel
            ->where('album_id', $albumId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            session()->setFlashdata('alert', ['type' => 'warning', 'msg' => 'Na toto album jste již recenzi napsali.']);
            return redirect()->to(base_url('album/' . $albumId));
        }

        $data = [
            'album_id' => $albumId,
            'user_id'  => $userId,
            'rating'   => $this->request->getPost('rating'),
            'body'     => $this->request->getPost('body'),
        ];

        if ($this->reviewModel->insert($data) !== false) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Recenze byla přidána.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se uložit recenzi.']);
        }

        return redirect()->to(base_url('album/' . $albumId));
    }

    /**
     * Provede soft delete recenze (nastaví deleted_at). Pouze pro admina.
     *
     * @param int $albumId   ID alba (pro přesměrování zpět)
     * @param int $id        ID recenze
     */
    public function delete(int $albumId, int $id)
    {
        if ($this->reviewModel->delete($id)) {
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Recenze byla smazána.']);
        } else {
            session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nepodařilo se smazat recenzi.']);
        }

        return redirect()->to(base_url('album/' . $albumId));
    }
}
