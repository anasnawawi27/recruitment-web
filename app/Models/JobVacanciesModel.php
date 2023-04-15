<?php

namespace App\Models;
use CodeIgniter\Model;

class JobVacanciesModel extends Model
{
    protected $table            = 'lowongan';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'gambar',
        'posisi',
        'qualifikasi',
        'tanggal_expired',
        'tampil',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
