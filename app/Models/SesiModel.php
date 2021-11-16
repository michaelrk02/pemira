<?php

namespace App\Models;

use CodeIgniter\Model;

class SesiModel extends Model {

    protected $table = 'sesi';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Sesi';

    protected $allowedFields = ['id', 'nama', 'waktu_buka', 'waktu_tutup'];

    public function viewProdi($id = NULL) {
        $qb = $this->builder();

        $qb->select('v.id, v.nama, v.waktu_buka, v.waktu_tutup, v.prodi_id, v.prodi_nama', FALSE);
        $qb->join('v_sesi_listprodi v', 'v.id = sesi.id', 'INNER', FALSE);

        if (isset($id)) {
            $qb->where('sesi.id', $id);
        }
        return $qb->get()->getResult();
    }

    public function fetch($draw, $start, $length, $search) {
        $result = [];

        $qb = $this->builder();

        $qb->select('id, nama, waktu_buka, waktu_tutup', FALSE);
        $result['recordsTotal'] = $qb->countAllResults(FALSE);

        $qb->like('nama', $search);
        $result['recordsFiltered'] = $qb->countAllResults(FALSE);

        $qb->limit($length, $start);

        $result['data'] = $qb->get()->getResult();
        $result['draw'] = $draw;

        return $result;
    }

}

