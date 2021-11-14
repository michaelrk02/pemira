<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Mahasiswa extends Entity {

    protected $datamap = [
        'NIM' => 'nim',
        'Nama' => 'nama',
        'IDProdi' => 'idprodi',
        'Angkatan' => 'angkatan',
        'SSO' => 'sso'
    ];

    public $Prodi = '';
    public $SudahVote = FALSE;

    public function getToken() {
        return md5(base64_encode($_ENV['pemira.token.secret']).':'.$this->NIM);
    }

}

