<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Pemilih as PemilihEntity;
use App\Libraries\Status;

class Pemilih extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        return redirect()->to('admin/pemilih/view');
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Data Pemilih', TRUE);
        echo view('admin/pemilih/data', [
            'idcapres' => $this->request->getGet('idcapres'),
            'idcaleg' => $this->request->getGet('idcaleg')
        ]);
        echo $this->viewFooter();
    }

    public function fetch() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $pemilihModel = model('App\Models\PemilihModel');

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $idcapres = $this->request->getGet('idcapres');
        $idcaleg = $this->request->getGet('idcaleg');
        $result = $pemilihModel->fetch($draw, $start, $length, $idcapres, $idcaleg);

        foreach ($result['data'] as &$data) {
            $obj = $data;
            $arr = [
                'token' => $obj->token,
                'valid' => ($obj->secret === md5(base64_encode($_ENV['pemira.token.secret'])) ? '<i class="fa fa-check green-text"></i>' : '<i class="fa fa-times red-text"></i>'),
                'prodi' => $obj->prodi,
                'idcapres' => $obj->idcapres,
                'idcaleg' => $obj->idcaleg
            ];
            $data = $arr;
        }

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($result));
    }

}

