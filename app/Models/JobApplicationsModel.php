<?php

namespace App\Models;
use CodeIgniter\Model;

class JobApplicationsModel extends Model
{
    protected $table            = 'lamaran';
    protected $returnType       = 'object';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'id_pelamar',
        'id_lowongan',
        'respond_input',
        'lolos_administrasi',
        'waktu_psikotest',
        'jumlah_soal_benar',
        'nilai_psikotest',
        'id_interview',
        'nilai_interview',
        'berpengalaman',
        'lama_pengalaman',
        'pas_photo',
        'cv',
        'status',
    ];
}
