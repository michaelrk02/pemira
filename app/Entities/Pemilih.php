<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Pemilih extends Entity {

    protected $datamap = [
        'Token' => 'token',
        'Secret' => 'secret',
        'IDProdi' => 'idprodi',
        'IDCapres' => 'idcapres',
        'IDCaleg' => 'idcaleg'
    ];

}

