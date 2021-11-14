<?php

namespace App\Models;

use CodeIgniter\Model;

class PemilihModel extends Model {

    protected $table = 'pemilih';
    protected $primaryKey = 'token';

    protected $returnType = 'App\Entities\Pemilih';

    protected $allowedFields = ['token', 'secret', 'idprodi', 'idcapres', 'idcaleg'];

    public function getTotalPemilih() {
        $qb = $this->builder();

        $qb->select('COUNT(*) jumlah');

        return $qb->get()->getRow()->jumlah;
    }

    public function fetch($draw, $start, $length, $idcapres, $idcaleg) {
        $result = [];

        $qb = $this->builder();

        $qb->select('pemilih.token, pemilih.secret, prodi.nama prodi, pemilih.idcapres, pemilih.idcaleg', FALSE);
        $qb->join('prodi', 'prodi.id = pemilih.idprodi', 'INNER', FALSE);

        if (isset($idcapres) && ($idcapres !== '')) { $qb->where('pemilih.idcapres', $idcapres); }
        if (isset($idcaleg) && ($idcaleg !== '')) { $qb->where('pemilih.idcaleg', $idcaleg); }

        $result['recordsTotal'] = $qb->countAllResults(FALSE);
        $result['recordsFiltered'] = $qb->countAllResults(FALSE);

        $qb->limit($length, $start);

        $result['data'] = $qb->get()->getResult();
        $result['draw'] = $draw;

        return $result;
    }

}

