<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'email', 'password_hash', 'role'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]',
        'email'    => 'required|valid_email|max_length[150]',
        'password_hash' => 'required|min_length[1]',
    ];

    /**
     * Najde uživatele podle emailu nebo uživatelského jména.
     * Používá se při přihlašování – lze zadat obojí.
     *
     * @param string $login  Email nebo username
     * @return object|null   Data uživatele (bez deleted), nebo null pokud nenalezen
     */
    public function findByLogin(string $login): ?object
    {
        return $this->where('email', $login)
                    ->orWhere('username', $login)
                    ->first();
    }
}
