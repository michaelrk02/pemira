<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdiModel extends Model {

    protected $table = 'prodi';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Prodi';

    public function viewSesi($id = NULL) {
        $qb = $this->builder();

        $qb->select('prodi.id, prodi.nama, sesi.id sesi_id, sesi.nama sesi_nama, sesi.waktu_buka sesi_waktu_buka, sesi.waktu_tutup sesi_waktu_tutup', FALSE);
        $qb->join('sesi_prodi', 'sesi_prodi.idprodi = prodi.id', 'INNER', FALSE);
        $qb->join('sesi', 'sesi.id = sesi_prodi.idsesi', 'INNER', FALSE);

        if (isset($id)) {
            return $qb->where('prodi.id', $id, FALSE)->get()->getRow();
        }
        return $qb->get()->getResult();
    }

}

