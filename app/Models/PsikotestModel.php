<?php

namespace App\Models;

use CodeIgniter\Model;

class PsikotestModel extends Model
{
    protected $table            = 'psikotest';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id_lowongan',
        'kategori_soal_ids',
        'waktu_pengerjaan',
        'point_persoal',
        'nilai_minimum',
    ];
}
