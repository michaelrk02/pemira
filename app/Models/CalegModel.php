<?php

namespace App\Models;

use CodeIgniter\Model;

class CalegModel extends Model {

    protected $table = 'caleg';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Caleg';

    protected $allowedFields = ['id', 'nama', 'idprodi', 'idfoto', 'metadata'];

    public function findByProdi($idprodi) {
        return $this->where('idprodi', $idprodi)->orWhere('idprodi', NULL)->findAll();
    }

    public function viewTotalPemilih($idprodi) {
        $qb = $this->builder();

        $qb->select('v.id, v.nama, v.prodi_id, v.prodi_nama, v.jumlah');
        $qb->join('v_caleg_pemilih v', 'v.id = caleg.id', 'INNER', FALSE);
        $qb->where('caleg.idprodi', $idprodi);
        $qb->orderBy('v.jumlah', 'DESC');

        return $qb->get()->getResult();
    }

    public function fetch($draw, $start, $length, $search) {
        $result = [];

        $qb = $this->builder();

        $qb->select('caleg.id, caleg.nama, prodi.nama prodi', FALSE);
        $qb->join('prodi', 'prodi.id = caleg.idprodi', 'LEFT', FALSE);
        $result['recordsTotal'] = $qb->countAllResults(FALSE);

        $qb->like('caleg.nama', $search);
        $result['recordsFiltered'] = $qb->countAllResults(FALSE);

        $qb->limit($length, $start);

        $result['data'] = $qb->get()->getResult();
        $result['draw'] = $draw;

        return $result;
    }

}

