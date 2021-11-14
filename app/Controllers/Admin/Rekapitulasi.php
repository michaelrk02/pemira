<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Capres;
use App\Libraries\Status;

class Rekapitulasi extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        return redirect()->to('admin/rekapitulasi/capres');
    }

    public function capres() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $capresModel = model('App\Models\CapresModel');

        $listCapres = $capresModel->viewTotalPemilih();
        foreach ($listCapres as &$capres) {
            $capres->obj = new Capres;
            $capres->obj->ID = $capres->id;
            $capres->obj->Nama = $capres->nama;
            $capres->obj->IDFoto = $capres->idfoto;
        }

        echo $this->viewHeader('Rekapitulasi Capres', TRUE);
        echo view('admin/rekapitulasi/tabs');
        echo view('admin/rekapitulasi/capres', [
            'listCapres' => $listCapres
        ]);
        echo $this->viewFooter();
    }

    public function caleg() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $prodiModel = model('App\Models\ProdiModel');
        $calegModel = model('App\Models\CalegModel');

        $listProdiCaleg = [];
        $listProdi = $prodiModel->findAll();
        foreach ($listProdi as $prodi) {
            $prodiCaleg = [];
            $prodiCaleg['prodi_id'] = $prodi->ID;
            $prodiCaleg['prodi_nama'] = $prodi->Nama;
            $prodiCaleg['ranking'] = $calegModel->viewTotalPemilih($prodi->ID);
            $listProdiCaleg[] = $prodiCaleg;
        }

        echo $this->viewHeader('Rekapitulasi Capres', TRUE);
        echo view('admin/rekapitulasi/tabs');
        echo view('admin/rekapitulasi/caleg', [
            'listProdiCaleg' => $listProdiCaleg
        ]);
        echo $this->viewFooter();
    }

}

