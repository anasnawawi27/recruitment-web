<?php

namespace App\Models;
use CodeIgniter\Model;

class RecruitersModel extends Model
{
    protected $table            = 'pelamar';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'nama_lengkap',
        'tanggal_lahir',
        'tempat_lahir',
        'email',
        'no_handphone_1',
        'no_handphone_2',
        'ktp',
        'file_vaksin_1',
        'tanggal_vaksin_1',
        'file_vaksin_2',
        'tanggal_vaksin_2',
        'file_vaksin_3',
        'tanggal_vaksin_3',
        'id_user',
    ];
}
