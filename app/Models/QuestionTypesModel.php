<?php

namespace App\Models;
use CodeIgniter\Model;

class QuestionTypesModel extends Model
{
    protected $table            = 'kategori_soal';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'kategori',
    ];
}
