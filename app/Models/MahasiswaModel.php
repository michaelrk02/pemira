<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model {

    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';

    protected $returnType = 'App\Entities\Mahasiswa';

    protected $allowedFields = ['nim', 'nama', 'idprodi', 'angkatan', 'sso'];

    public function findBySSO($sso) {
        return $this->where('sso', $sso)->first();
    }

    public function viewFull($nim) {
        $result = [];

        $qb = $this->builder();

        $qb->select('v.nim, v.nama,  v.prodi, v.angkatan, v.sso', FALSE);
        $qb->join('v_mahasiswa_full v', 'v.nim = mahasiswa.nim', 'INNER', FALSE);
        $qb->where('mahasiswa.nim', $nim);

        return $qb->get()->getRow();
    }

    public function fetch($draw, $start, $length, $search) {
        $result = [];

        $qb = $this->builder();

        $qb->select('mahasiswa.nim, mahasiswa.nama, prodi.nama prodi, mahasiswa.angkatan', FALSE);
        $qb->join('prodi', 'prodi.id = mahasiswa.idprodi', 'INNER', FALSE);
        $result['recordsTotal'] = $qb->countAllResults(FALSE);

        $qb->like('mahasiswa.nama', $search);
        $result['recordsFiltered'] = $qb->countAllResults(FALSE);

        $qb->limit($length, $start);

        $result['data'] = $qb->get()->getResult();
        $result['draw'] = $draw;

        return $result;
    }

    public function reset() {
        $qb = $this->builder();

        $qb->emptyTable();
    }

}

