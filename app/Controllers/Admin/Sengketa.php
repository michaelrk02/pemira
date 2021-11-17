<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Libraries\Status;

class Sengketa extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Layanan Sengketa', TRUE);
        echo view('admin/sengketa/index');
        echo $this->viewFooter();
    }

    public function cekValiditasBukti() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->request->getPost('submit') == 1) {
            $nim = $this->request->getPost('nim');
            $idcapres = $this->request->getPost('idcapres');
            $idcaleg = $this->request->getPost('idcaleg');
            $signature = $this->request->getPost('signature');
            $validSignature = md5($nim.':'.$idcapres.':'.$idcaleg.':'.base64_encode($_ENV['pemira.token.secret']));

            if ($signature === $validSignature) {
                $this->session->set('status', new Status('success', 'Bukti tersebut valid'));
            } else {
                $this->session->set('status', new Status('error', 'Bukti tersebut tidak valid'));
            }
        }

        return redirect()->to('admin/sengketa');
    }

}

