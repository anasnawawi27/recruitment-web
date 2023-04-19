<?php

namespace App\Models;

use CodeIgniter\Model;

class InterviewModel extends Model
{
    protected $table            = 'interview';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'waktu',
        'pewawancara',
        'konten_email',
    ];
}
