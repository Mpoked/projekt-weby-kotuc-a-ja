<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * AuthFilter
 *
 * Chrání routy před nepřihlášenými uživateli.
 * S argumentem 'admin' navíc ověřuje roli uživatele.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('logged_in')) {
            session()->setFlashdata('alert', ['type' => 'warning', 'msg' => 'Pro přístup k této stránce se musíte přihlásit.']);
            return redirect()->to(base_url('login'));
        }

        if ($arguments && in_array('admin', $arguments)) {
            if (session()->get('role') !== 'admin') {
                session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Nemáte oprávnění pro tuto akci.']);
                return redirect()->to(base_url('/'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nic
    }
}
