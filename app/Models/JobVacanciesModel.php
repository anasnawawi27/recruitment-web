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
        'deskripsi',
        'batas_tanggal',
        'qualifikasi',
        'id_interview',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
