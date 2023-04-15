<?php

namespace App\Models;
use CodeIgniter\Model;

class QuestionsModel extends Model
{
    protected $table            = 'soal';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id_kategori',
        'gambar',
        'pertanyaan',
        'jawaban'
    ];
}
