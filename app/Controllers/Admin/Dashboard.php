<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Libraries\Status;

class Dashboard extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Admin Dashboard', TRUE);
        echo view('admin/dashboard/index');
        echo $this->viewFooter();
    }

    public function getDashboardInfo() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $prodiModel = model('App\Models\ProdiModel');
        $sesiModel = model('App\Models\SesiModel');
        $capresModel = model('App\Models\CapresModel');
        $partaiModel = model('App\Models\PartaiModel');
        $calegModel = model('App\Models\CalegModel');
        $mahasiswaModel = model('App\Models\MahasiswaModel');
        $pemilihModel = model('App\Models\PemilihModel');

        $this->response->setContentType('application/json');

        $info = [];
        $info['jumlah'] = [
            [
                'name' => 'Prodi',
                'value' => $prodiModel->builder()->countAll(),
                'icon' => 'map-marker-alt',
                'url' => site_url('admin/prodi/view')
            ],
            [
                'name' => 'Sesi',
                'value' => $sesiModel->builder()->countAll(),
                'icon' => 'clock',
                'url' => site_url('admin/sesi/view')
            ],
            [
                'name' => 'Capres',
                'value' => $capresModel->builder()->countAll(),
                'icon' => 'user-friends',
                'url' => site_url('admin/capres/view')
            ],
            [
                'name' => 'Partai',
                'value' => $partaiModel->builder()->countAll(),
                'icon' => 'flag',
                'url' => site_url('admin/partai/view')
            ],
            [
                'name' => 'Caleg',
                'value' => $calegModel->builder()->countAll(),
                'icon' => 'user',
                'url' => site_url('admin/caleg/view')
            ],
            [
                'name' => 'Mahasiswa',
                'value' => $mahasiswaModel->builder()->countAll(),
                'icon' => 'users',
                'url' => site_url('admin/mahasiswa/view')
            ],
            [
                'name' => 'Suara Masuk',
                'value' => $pemilihModel->builder()->countAll(),
                'icon' => 'vote-yea',
                'url' => site_url('admin/pemilih/view')
            ]
        ];
        $info['statistik'] = $prodiModel->viewStatistik();

        return $this->response->setStatusCode(200)->setBody(json_encode($info));
    }

}

