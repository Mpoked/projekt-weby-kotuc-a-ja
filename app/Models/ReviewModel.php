<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'review';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['album_id', 'user_id', 'rating', 'body'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[10]',
        'body'   => 'required|min_length[1]',
    ];
    protected $validationMessages = [
        'rating' => [
            'required'                => 'Hodnocení je povinné.',
            'greater_than_equal_to'   => 'Hodnocení musí být minimálně 1.',
            'less_than_equal_to'      => 'Hodnocení musí být nejvýše 10.',
        ],
    ];
}
