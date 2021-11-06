<?php

namespace App\Models;

use CodeIgniter\Model;

class CapresModel extends Model {

    protected $table = 'capres';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Capres';

    public function viewTotalPemilih($id = NULL) {
        $qb = $this->builder();

        $qb->select('capres.id, capres.nama, IFNULL(COUNT(*), 0) AS jumlah', FALSE);
        $qb->join('pemilih_capres', 'pemilih_capres.idcapres = capres.id', 'LEFT', FALSE);
        $qb->groupBy('capres.id, capres.nama');

        if (isset($id)) {
            return $qb->where('capres.id', $id, FALSE)->getRow();
        }
        return $qb->get()->getResult();
    }

}

