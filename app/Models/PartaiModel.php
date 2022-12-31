<?php

namespace App\Models;

use CodeIgniter\Model;

class PartaiModel extends Model {

    protected $table = 'partai';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Partai';

    protected $allowedFields = ['id', 'nama', 'idfoto'];

    public function viewTotalPemilih() {
        $qb = $this->builder();

        $qb->select('v.id, v.nama, v.idfoto, v.jumlah', FALSE);
        $qb->join('v_partai_pemilih v', 'v.id = partai.id', 'INNER', FALSE);

        return $qb->get()->getResult();
    }

    public function fetch($draw, $start, $length, $search) {
        $result = [];

        $qb = $this->builder();

        $qb->select('id, nama', FALSE);
        $result['recordsTotal'] = $qb->countAllResults(FALSE);

        $qb->like('nama', $search);
        $result['recordsFiltered'] = $qb->countAllResults(FALSE);

        $qb->limit($length, $start);

        $result['data'] = $qb->get()->getResult();
        $result['draw'] = $draw;

        return $result;
    }

}

