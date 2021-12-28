<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

use App\Libraries\ResourceManager;
use App\Libraries\WebToken;

class Caleg extends Entity {

    protected $datamap = [
        'ID' => 'id',
        'Nama' => 'nama',
        'IDProdi' => 'idprodi',
        'IDFoto' => 'idfoto',
        'Metadata' => 'metadata'
    ];

    public function photoExists() {
        $manager = new ResourceManager();

        return $manager->exists($this->IDFoto);
    }

    public function getPhotoURL() {
        $token = WebToken::fromData(['expired' => time() + 5], ['id' => $this->IDFoto]);

        return site_url('resource').'?token='.urlencode($token->toString());
    }

}

