<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class UserController extends BaseController {

    protected $userLogin;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        $loginNIM = $this->session->get('user_login');
        if ($loginNIM !== NULL) {
            $mahasiswaModel = model('App\Models\MahasiswaModel');

            $this->userLogin = $mahasiswaModel->find($loginNIM);
            if ($this->userLogin !== NULL) {
                $pemilihModel = model('App\Models\PemilihModel');

                $mhsFull = $mahasiswaModel->viewFull($loginNIM);

                $this->userLogin->Prodi = $mhsFull->prodi;

                $pemilih = $pemilihModel->find($this->userLogin->Token);
                $this->userLogin->SudahVote = isset($pemilih);
            } else {
                $this->session->remove('user_login');
                return $this->redirect->to(site_url('user/home'));
            }
        }

        $this->menus[] = ['name' => 'Beranda', 'site' => 'user/home'];

        if ($this->userLogin !== NULL) {
            $this->menus[] = ['name' => 'Voting', 'site' => 'user/vote'];
            $this->menus[] = ['name' => 'Keluar', 'site' => 'user/auth/logout'];
        }
    }

}

