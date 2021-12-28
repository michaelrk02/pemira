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

        $bukti = [
            'nim' => '',
            'idcapres' => '',
            'idcaleg' => '',
            'timestamp' => '',
            'signature' => ''
        ];

        if ($this->request->getPost('submit') == 1) {
            $nim = $this->request->getPost('nim');
            $idcapres = $this->request->getPost('idcapres');
            $idcaleg = $this->request->getPost('idcaleg');
            $timestamp = $this->request->getPost('timestamp');
            $signature = $this->request->getPost('signature');

            $validSignature = '';
            if ($timestamp == '') {
                // backward-compatibility purpose
                $validSignature = md5($nim.':'.$idcapres.':'.$idcaleg.':'.base64_encode($_ENV['pemira.token.secret']));
            } else {
                $validSignature = md5($nim.':'.$idcapres.':'.$idcaleg.':'.$timestamp.':'.base64_encode($_ENV['pemira.token.secret']));
            }

            if ($signature === $validSignature) {
                $this->session->set('status', new Status('success', 'Bukti tersebut valid'));
            } else {
                $this->session->set('status', new Status('error', 'Bukti tersebut tidak valid'));
            }
            $this->initStatus();

            $bukti = [
                'nim' => $nim,
                'idcapres' => $idcapres,
                'idcaleg' => $idcaleg,
                'timestamp' => $timestamp,
                'signature' => $signature
            ];
        }

        echo $this->viewHeader('Layanan Sengketa', TRUE);
        echo view('admin/sengketa/index', ['bukti' => $bukti]);
        echo $this->viewFooter();
    }

}

