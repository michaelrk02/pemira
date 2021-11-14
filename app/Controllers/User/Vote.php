<?php

namespace App\Controllers\User;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\UserController;
use App\Entities\Pemilih;
use App\Libraries\Status;

class Vote extends UserController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        if ($this->userLogin === NULL) {
            return redirect()->to('user/auth/login');
        }

        $prodiModel = model('App\Models\ProdiModel');
        if (!$prodiModel->canVote($this->userLogin->IDProdi)) {
            $this->session->set('status', new Status('error', 'Sekarang bukan jadwal prodi anda untuk memilih'));
            return redirect()->to('user/home');
        }

        $pemilihModel = model('App\Models\PemilihModel');

        $pemilih = $pemilihModel->find($this->userLogin->getToken());
        if (isset($pemilih)) {
            $this->session->set('status', new Status('error', 'Anda tercatat sudah melakukan voting sebelumnya. Dengan demikian, anda hanya dapat memilih satu kali saja'));
            return redirect()->to('user/home');
        }

        $capresModel = model('App\Models\CapresModel');
        $calegModel = model('App\Models\CalegModel');

        if ($this->request->getPost('submit') == 1) {
            $jmlCaleg = count($calegModel->findByProdi($this->userLogin->IDProdi));

            $idcapres = $this->request->getPost('idcapres');
            $idcaleg = $this->request->getPost('idcaleg');

            if ($idcapres === '') { $idcapres = NULL; }
            if ($idcaleg === '') { $idcaleg = NULL; }

            if (isset($idcapres) && ((!isset($idcaleg) && ($jmlCaleg == 0)) || (($jmlCaleg > 0) && isset($idcaleg)))) {
                $this->session->set('status', new Status('success', 'Pilihan berhasil disimpan. Terima kasih telah menggunakan hak pilih anda'));

                $pemilih = new Pemilih();
                $pemilih->Token = $this->userLogin->getToken();
                $pemilih->Secret = md5(base64_encode($_ENV['pemira.token.secret']));
                $pemilih->IDProdi = $this->userLogin->IDProdi;
                $pemilih->IDCapres = $idcapres;
                $pemilih->IDCaleg = $idcaleg;
                $pemilihModel->insert($pemilih);

                return redirect()->to('user/home');
            } else {
                $this->session->set('status', new Status('error', 'Anda harus memilih capres dan caleg (jika ada)!'));
            }
        }
        $this->initStatus();

        echo $this->viewHeader('Vote');
        echo view('user/vote/index', [
            'login' => $this->userLogin,
            'listCapres' => $capresModel->findAll(),
            'listCaleg' => $calegModel->findByProdi($this->userLogin->IDProdi)
        ]);
        echo $this->viewFooter();
    }

}

