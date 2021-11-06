<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Capres extends Entity {

    protected $datamap = [
        'ID' => 'id',
        'Nama' => 'nama',
        'Visi' => 'visi',
        'Misi' => 'misi'
    ];

}

