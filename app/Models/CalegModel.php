<?php

namespace App\Models;

use CodeIgniter\Model;

class CalegModel extends Model {

    protected $table = 'caleg';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Caleg';

    protected $allowedFields = ['id', 'nama', 'idprodi', 'idfoto'];

    public function findByProdi($idprodi) {
        return $this->where('idprodi', $idprodi)->findAll();
    }

    public function viewTotalPemilih($idprodi) {
        $qb = $this->builder();

        $qb->select('caleg.id, caleg.nama, prodi.nama prodi, SUM(CASE WHEN pemilih.token IS NULL THEN 0 ELSE 1 END) jumlah', FALSE);
        $qb->join('pemilih', 'pemilih.idcaleg = caleg.id', 'LEFT', FALSE);
        $qb->join('prodi', 'prodi.id = caleg.idprodi', 'LEFT', FALSE);
        $qb->where('prodi.id', $idprodi);
        $qb->groupBy('caleg.id, caleg.nama', FALSE);
        $qb->orderBy('jumlah', 'DESC');

        return $qb->get()->getResult();
    }

    public function fetch($draw, $start, $length, $search) {
        $result = [];

        $qb = $this->builder();

        $qb->select('caleg.id, caleg.nama, prodi.nama prodi', FALSE);
        $qb->join('prodi', 'prodi.id = caleg.idprodi', 'INNER', FALSE);
        $result['recordsTotal'] = $qb->countAllResults(FALSE);

        $qb->like('caleg.nama', $search);
        $result['recordsFiltered'] = $qb->countAllResults(FALSE);

        $qb->limit($length, $start);

        $result['data'] = $qb->get()->getResult();
        $result['draw'] = $draw;

        return $result;
    }

}

