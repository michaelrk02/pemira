<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Pemilih as PemilihEntity;
use App\Libraries\Status;
use App\Libraries\WebToken;

class Pemilih extends AdminController {

    protected $tokenSecretHash;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        $this->tokenSecretHash = md5(base64_encode($_ENV['pemira.token.secret']));
    }

    public function index() {
        return redirect()->to('admin/pemilih/view');
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $resetToken = WebToken::fromData(['expired' => time() + 1800], [])->toString();
        $pemilihModel = model('App\Models\PemilihModel');

        echo $this->viewHeader('Data Pemilih', TRUE);
        echo view('admin/pemilih/data', [
            'tokenSecretHash' => $this->tokenSecretHash,
            'normal' => $pemilihModel->isNormal(),
            'idcapres' => $this->request->getGet('idcapres'),
            'idpartai' => $this->request->getGet('idpartai'),
            'idcaleg' => $this->request->getGet('idcaleg'),
            'resetToken' => $resetToken
        ]);
        echo $this->viewFooter();
    }

    public function fetch() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $pemilihModel = model('App\Models\PemilihModel');

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $idcapres = $this->request->getGet('idcapres');
        $idpartai = $this->request->getGet('idpartai');
        $idcaleg = $this->request->getGet('idcaleg');
        $result = $pemilihModel->fetch($draw, $start, $length, $idcapres, $idpartai, $idcaleg);

        foreach ($result['data'] as &$data) {
            $obj = $data;
            $arr = [
                'token' => $obj->token,
                'normal' => ($obj->secret === $this->tokenSecretHash ? '<i class="fa fa-check green-text"></i> Yes' : '<i class="fa fa-times red-text"></i> No'),
                'valid' => ($obj->signature === md5($obj->token.':'.$obj->idcapres.':'.$obj->idpartai.':'.$obj->idcaleg.':'.base64_encode($_ENV['pemira.token.secret'])) ? '<i class="fa fa-check green-text"></i> Yes' : '<i class="fa fa-times red-text"></i> No'),
                'prodi' => $obj->prodi,
                'idcapres' => $obj->idcapres,
                'idpartai' => $obj->idpartai,
                'idcaleg' => $obj->idcaleg
            ];
            $data = $arr;
        }

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($result));
    }

    public function cekStatistik() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $mahasiswaModel = model('App\Models\MahasiswaModel');
        $pemilihModel = model('App\Models\PemilihModel');

        $mhsArray = $mahasiswaModel->findAll();

        $tokenCount = $pemilihModel->builder()->countAll();
        $suaraCount = 0;
        foreach ($mhsArray as $mhs) {
            if ($pemilihModel->find($mhs->getToken()) !== NULL) {
                $suaraCount++;
            }
        }

        $data = [
            'tokenCount' => $tokenCount,
            'suaraCount' => $suaraCount
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($data));
    }

    public function cekTokenIlegal() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $mahasiswaModel = model('App\Models\MahasiswaModel');
        $pemilihModel = model('App\Models\PemilihModel');

        $mhsArray = $mahasiswaModel->findAll();

        $suaraArray = [];
        foreach ($mhsArray as $mhs) {
            $suara = $pemilihModel->find($mhs->getToken());
            if (isset($suara)) {
                if ($suara->Secret === md5(base64_encode($_ENV['pemira.token.secret']))) {
                    if ($suara->Signature === md5($suara->Token.':'.$suara->IDCapres.':'.$suara->IDCaleg.':'.base64_encode($_ENV['pemira.token.secret']))) {
                        $suaraArray[] = '\''.$mhs->getToken().'\'';
                    }
                }
            }
        }

        $tokenIlegal = [];
        if (count($suaraArray) > 0) {
            $suaraArray = '('.implode(',', $suaraArray).')';
            $tokenIlegal = $pemilihModel->builder()->where('token NOT IN '.$suaraArray, NULL, FALSE)->get()->getResult();
        } else {
            $tokenIlegal = $pemilihModel->builder()->get()->getResult();
        }

        $capresIlegal = [];
        $calegIlegal = [];

        foreach ($tokenIlegal as $token) {
            if (isset($capresIlegal[$token->idcapres])) {
                $capresIlegal[$token->idcapres]++;
            } else {
                $capresIlegal[$token->idcapres] = 1;
            }
            if (($token->idcaleg !== NULL) && ($token->idcaleg !== '')) {
                if (isset($calegIlegal[$token->idcaleg])) {
                    $calegIlegal[$token->idcaleg]++;
                } else {
                    $calegIlegal[$token->idcaleg] = 1;
                }
            }
        }

        echo view('admin/pemilih/token_ilegal', [
            'tokenIlegal' => $tokenIlegal,
            'capresIlegal' => $capresIlegal,
            'calegIlegal' => $calegIlegal
        ]);
    }

    public function reset() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $token = WebToken::fromString($this->request->getGet('token'));
        if (isset($token)) {
            $pemilihModel = model('App\Models\PemilihModel');
            $pemilihModel->builder()->emptyTable();
            $this->session->set('status', new Status('success', 'Berhasil reset seluruh data pemilih'));
        } else {
            $this->session->set('status', new Status('error', 'Token reset tidak valid. Silakan refresh halaman ini kemudian lakukan reset lagi'));
        }
        return redirect()->to('admin/pemilih/view');
    }

}

