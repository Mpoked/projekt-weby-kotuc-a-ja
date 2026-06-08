<?php

namespace App\Models;

use CodeIgniter\Model;

class AlbumModel extends Model
{
    protected $table            = 'album';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'artist_id',
        'title',
        'release_date',
        'cover_image',
        'description',
        'label',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'title'     => 'required|min_length[1]|max_length[255]',
        'artist_id' => 'required|integer|greater_than[0]',
    ];
    protected $validationMessages = [
        'title'     => ['required' => 'Název alba je povinný.'],
        'artist_id' => ['required' => 'Vyberte umělce.', 'greater_than' => 'Vyberte umělce.'],
    ];
}
