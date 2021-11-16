<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdiModel extends Model {

    protected $table = 'prodi';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Prodi';

    protected $allowedFields = ['id', 'nama'];

    public function viewSesi($id = NULL) {
        $qb = $this->builder();

        $qb->select('v.id, v.nama, v.sesi_id, v.sesi_nama, v.sesi_waktu_buka, v.sesi_waktu_tutup', FALSE);
        $qb->join('v_prodi_listsesi v', 'v.id = prodi.id', 'INNER', FALSE);

        if (isset($id)) {
            $qb->where('prodi.id', $id, FALSE);
        }
        return $qb->get()->getResult();
    }

    public function canVote($id) {
        $qb = $this->builder();

        $qb->select('v.canvote');
        $qb->join('v_prodi_canvote v', 'v.id = prodi.id', 'INNER', FALSE);
        $qb->where('prodi.id', $id);
        $row = $qb->get()->getRow();

        return !empty($row->canvote);
    }

    public function viewStatistik() {
        $qb = $this->builder();

        $qb->select('v.id, v.nama, v.pemilih, v.useraktif, v.kuota', FALSE);
        $qb->join('v_prodi_statistik v', 'v.id = prodi.id', 'INNER', FALSE);

        return $qb->get()->getResult();
    }

    public function getTotalKuota() {
        $qb = $this->builder();

        $qb->select('SUM(v.jumlah) jumlah', FALSE);
        $qb->join('v_prodi_kuota v', 'v.id = prodi.id', 'INNER', FALSE);

        return $qb->get()->getRow()->jumlah;
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

    public function findSesi($idprodi, $idsesi) {
        $qb = $this->builder('sesi_prodi');

        $qb->select('idprodi, idsesi', FALSE);
        $qb->where(['idprodi' => $idprodi, 'idsesi' => $idsesi]);

        return $qb->get()->getRow();
    }

    public function insertSesi($idprodi, $idsesi) {
        $qb = $this->builder('sesi_prodi');

        $qb->insert(['idprodi' => $idprodi, 'idsesi' => $idsesi]);
    }

    public function deleteSesi($idprodi, $idsesi) {
        $qb = $this->builder('sesi_prodi');

        $qb->where(['idprodi' => $idprodi, 'idsesi' => $idsesi])->delete();
    }

}

