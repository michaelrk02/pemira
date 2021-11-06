<?php

namespace App\Models;

use CodeIgniter\Model;

class CalegModel extends Model {

    protected $table = 'caleg';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Caleg';

    public function viewTotalPemilih($id = NULL) {
        $qb = $this->builder();

        $qb->select('caleg.id, caleg.nama, IFNULL(COUNT(*), 0) AS jumlah', FALSE);
        $qb->join('pemilih_caleg', 'pemilih_caleg.idcapres = caleg.id', 'LEFT', FALSE);
        $qb->groupBy('caleg.id, caleg.nama');

        if (isset($id)) {
            return $qb->where('caleg.id', $id, FALSE)->get()->getRow();
        }
        return $qb->get()->getResult();
    }

}

