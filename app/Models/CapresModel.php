<?php

namespace App\Models;

use CodeIgniter\Model;

class CapresModel extends Model {

    protected $table = 'capres';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Capres';

    protected $allowedFields = ['id', 'nama', 'visi', 'misi', 'idfoto'];

    public function viewTotalPemilih() {
        $qb = $this->builder();

        $qb->select('capres.id, capres.nama, capres.idfoto, SUM(CASE WHEN pemilih.token IS NULL THEN 0 ELSE 1 END) jumlah', FALSE);
        $qb->join('pemilih', 'pemilih.idcapres = capres.id', 'LEFT', FALSE);
        $qb->groupBy('capres.id, capres.nama, capres.idfoto', FALSE);

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

