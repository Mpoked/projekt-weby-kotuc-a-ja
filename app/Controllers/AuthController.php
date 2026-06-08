<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * AuthController
 *
 * Spravuje přihlášení, registraci a odhlášení uživatelů.
 * Přihlásit lze jak emailem, tak uživatelským jménem.
 */
class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Zobrazí přihlašovací formulář.
     * Pokud je uživatel již přihlášen, přesměruje na úvodní stránku.
     */
    public function login(): string
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        return view('auth/login', ['title' => 'Přihlášení']);
    }

    /**
     * Zpracuje POST data přihlašovacího formuláře.
     * Podporuje přihlášení emailem i uživatelským jménem.
     * Po úspěchu nastaví session a přesměruje, při chybě vrátí na formulář.
     */
    public function loginPost()
    {
        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByLogin($login);

        if ($user && password_verify($password, $user->password_hash)) {
            session()->set([
                'user_id'   => $user->id,
                'username'  => $user->username,
                'role'      => $user->role,
                'logged_in' => true,
            ]);
            session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Vítejte, ' . esc($user->username) . '!']);
            return redirect()->to(base_url('/'));
        }

        session()->setFlashdata('alert', ['type' => 'danger', 'msg' => 'Neplatné přihlašovací údaje.']);
        return redirect()->back()->withInput();
    }

    /**
     * Zobrazí registrační formulář.
     */
    public function register(): string
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        return view('auth/register', ['title' => 'Registrace']);
    }

    /**
     * Zpracuje POST data registračního formuláře.
     * Validuje jedinečnost emailu a username, délku hesla.
     * Heslo se ukládá jako bcrypt hash.
     */
    public function registerPost()
    {
        $username = trim($this->request->getPost('username'));
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('password_confirm');

        $errors = [];

        if (mb_strlen($username) < 3) {
            $errors[] = 'Uživatelské jméno musí mít alespoň 3 znaky.';
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Zadejte platný email.';
        }
        if (mb_strlen($password) < 8) {
            $errors[] = 'Heslo musí mít alespoň 8 znaků.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Hesla se neshodují.';
        }
        if ($this->userModel->where('email', $email)->first()) {
            $errors[] = 'Tento email je již zaregistrován.';
        }
        if ($this->userModel->where('username', $username)->first()) {
            $errors[] = 'Toto uživatelské jméno je již obsazeno.';
        }

        if (! empty($errors)) {
            session()->setFlashdata('errors', $errors);
            return redirect()->back()->withInput();
        }

        $this->userModel->insert([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'user',
        ]);

        session()->setFlashdata('alert', ['type' => 'success', 'msg' => 'Registrace proběhla úspěšně. Přihlaste se.']);
        return redirect()->to(base_url('login'));
    }

    /**
     * Odhlásí přihlášeného uživatele – zničí session a přesměruje.
     */
    public function logout()
    {
        session()->destroy();
        session()->setFlashdata('alert', ['type' => 'info', 'msg' => 'Byli jste odhlášeni.']);
        return redirect()->to(base_url('login'));
    }
}
