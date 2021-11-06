<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model {

    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';

    protected $returnType = 'App\Entities\Mahasiswa';

    protected $allowedFields = ['sso'];

    public function findBySSO($sso) {
        return $this->where('sso', $sso)->first();
    }

    public function viewFull($id) {
        $qb = $this->builder();

        $qb->select('mahasiswa.nim, mahasiswa.sso, mahasiswa.nama, prodi.nama prodi, mahasiswa.angkatan', FALSE);
        $qb->join('prodi', 'prodi.id = mahasiswa.idprodi', 'INNER', FALSE);

        return $qb->get()->getRow();
    }

}

