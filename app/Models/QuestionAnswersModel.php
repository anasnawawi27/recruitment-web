<?php

namespace App\Models;
use CodeIgniter\Model;

class QuestionAnswersModel extends Model
{
    protected $table            = 'pilihan_soal';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id_soal',
        'pilihan_jawaban',
        'gambar',
        'urutan',
        'alphabet'
    ];
}
