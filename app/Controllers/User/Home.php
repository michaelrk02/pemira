<?php

namespace App\Controllers\User;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\UserController;

class Home extends UserController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        $prodiModel = model('App\Models\ProdiModel');

        echo $this->viewHeader('Home');
        echo view('user/home/index', [
            'login' => $this->userLogin,
            'canVote' => isset($this->userLogin) ? $prodiModel->canVote($this->userLogin->IDProdi) : FALSE,
            'listSesi' => isset($this->userLogin) ? $prodiModel->viewSesi($this->userLogin->IDProdi) : NULL
        ]);
        echo $this->viewFooter();
    }

    public function getInfoSesi() {
        $sesiModel = model('App\Models\SesiModel');

        $id = $this->request->getGet('id');
        $sesi = $sesiModel->find($id);
        if (isset($sesi)) {
            echo view('user/home/get_info_sesi', [
                'sesi' => $sesi
            ]);
        } else {
            echo 'ID sesi tidak valid';
        }
    }

    public function getLiveCount() {
        $prodiModel = model('App\Models\ProdiModel');
        $pemilihModel = model('App\Models\PemilihModel');

        $totalPemilih = $pemilihModel->getTotalPemilih();
        $totalKuota = $prodiModel->getTotalKuota();

        $sebaranPemilih = $prodiModel->viewPemilih();

        echo view('user/home/get_live_count', [
            'totalPemilih' => $totalPemilih,
            'totalKuota' => $totalKuota,
            'sebaranPemilih' => $sebaranPemilih
        ]);
    }

}

