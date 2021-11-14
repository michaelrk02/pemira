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

        $qb->select('prodi.id, prodi.nama, sesi.id sesi_id, sesi.nama sesi_nama, sesi.waktu_buka sesi_waktu_buka, sesi.waktu_tutup sesi_waktu_tutup', FALSE);
        $qb->join('sesi_prodi', 'sesi_prodi.idprodi = prodi.id', 'INNER', FALSE);
        $qb->join('sesi', 'sesi.id = sesi_prodi.idsesi', 'INNER', FALSE);

        if (isset($id)) {
            $qb->where('prodi.id', $id, FALSE);
        }
        return $qb->get()->getResult();
    }

    public function canVote($id) {
        $qb = $this->builder();

        $qb->select('COUNT(*) memenuhi', FALSE);
        $qb->join('sesi_prodi', 'sesi_prodi.idprodi = prodi.id', 'LEFT', FALSE);
        $qb->join('sesi', 'sesi.id = sesi_prodi.idsesi', 'LEFT', FALSE);
        $qb->where('prodi.id', $id, FALSE);
        $qb->where('sesi.waktu_buka <= UNIX_TIMESTAMP(NOW())', NULL, FALSE);
        $qb->where('UNIX_TIMESTAMP(NOW()) < sesi.waktu_tutup', NULL, FALSE);
        $row = $qb->get()->getRow();

        return $row->memenuhi > 0;
    }

    public function viewPemilih() {
        $qb = $this->builder();

        $qb->select('prodi.id, prodi.nama, v_kuotaprodi.kuota, SUM(CASE WHEN pemilih.token IS NULL THEN 0 ELSE 1 END) jumlah', FALSE);
        $qb->join('v_kuotaprodi', 'v_kuotaprodi.id = prodi.id', 'LEFT', FALSE);
        $qb->join('pemilih', 'pemilih.idprodi = prodi.id', 'LEFT', FALSE);
        $qb->groupBy('prodi.id, prodi.nama', FALSE);

        return $qb->get()->getResult();
    }

    public function getTotalKuota() {
        $qb = $this->builder();

        $qb->select('SUM(v_kuotaprodi.kuota) jumlah', FALSE);
        $qb->join('v_kuotaprodi', 'v_kuotaprodi.id = prodi.id', 'INNER', FALSE);

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

