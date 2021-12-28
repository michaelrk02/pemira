<?php

namespace App\Models;

use CodeIgniter\Model;

class CapresModel extends Model {

    protected $table = 'capres';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Capres';

    protected $allowedFields = ['id', 'nama', 'visi', 'misi', 'idfoto', 'metadata'];

    public function viewTotalPemilih() {
        $qb = $this->builder();

        $qb->select('v.id, v.nama, v.idfoto, v.jumlah', FALSE);
        $qb->join('v_capres_pemilih v', 'v.id = capres.id', 'INNER', FALSE);

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

