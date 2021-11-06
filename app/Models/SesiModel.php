<?php

namespace App\Models;

use CodeIgniter\Model;

class SesiModel extends Model {

    protected $table = 'sesi';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Sesi';

    public function viewProdi($id = NULL) {
        $qb = $this->builder();

        $qb->select('sesi.id, sesi.nama, sesi.waktu_buka, sesi.waktu_tutup, prodi.id prodi_id, prodi.nama prodi_nama', FALSE);
        $qb->join('sesi_prodi', 'sesi_prodi.idsesi = sesi', 'INNER', FALSE);
        $qb->join('prodi', 'prodi.id = sesi_prodi.idprodi', 'INNER', FALSE);

        if (isset($id)) {
            return $qb->where('id', $id)->get()->getRow();
        }
        return $qb->get()->getResult();
    }

}

