<?php

namespace App\Models;
use CodeIgniter\Model;

class JobApplicationsModel extends Model
{
    protected $table            = 'lamaran';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;
    protected $allowedFields    = [
        'id_pelamar',
        'id_lowongan',
        'id_psikotest',
        'nilai_psikotest',
        'berpengalaman',
        'lama_pengalaman',
        'pas_photo',
        'cv',
        'status',
    ];
}
