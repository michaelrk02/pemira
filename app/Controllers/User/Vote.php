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
                $caleg = $calegModel->find($idcaleg);
                if ((($caleg->IDProdi === NULL) || ($caleg->IDProdi === '')) || ($caleg->IDProdi == $this->userLogin->IDProdi)) {
                    $this->session->set('status', new Status('success', 'Pilihan berhasil disimpan. Terima kasih telah menggunakan hak pilih anda. Anda juga dapat mengunduh bukti pemilihan anda apabila diperlukan'));

                    $pemilih = new Pemilih();
                    $pemilih->Token = $this->userLogin->getToken();
                    $pemilih->Secret = md5(base64_encode($_ENV['pemira.token.secret']));
                    $pemilih->Signature = md5($pemilih->Token.':'.$idcapres.':'.$idcaleg.':'.base64_encode($_ENV['pemira.token.secret']));
                    $pemilih->IDProdi = $this->userLogin->IDProdi;
                    $pemilih->IDCapres = $idcapres;
                    $pemilih->IDCaleg = $idcaleg;
                    $pemilihModel->insert($pemilih);

                    return redirect()->to('user/home');
                } else {
                    $this->session->set('status', new Status('error', 'Anda tidak bisa memilih caleg di luar yang tersedia untuk prodi anda!'));
                }
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

    public function downloadBukti() {
        if ($this->userLogin === NULL) {
            return redirect()->to('user/auth/login');
        }

        $pemilihModel = model('App\Models\PemilihModel');

        $pemilih = $pemilihModel->find($this->userLogin->getToken());
        if (isset($pemilih)) {
            $capresModel = model('App\Models\CapresModel');
            $calegModel = model('App\Models\CalegModel');

            $nim = $this->userLogin->NIM;
            $idcapres = $pemilih->IDCapres;
            $idcaleg = $pemilih->IDCaleg;
            $timestamp = time();
            $signature = md5($nim.':'.$idcapres.':'.$idcaleg.':'.$timestamp.':'.base64_encode($_ENV['pemira.token.secret']));

            $file = '';
            $file .= 'Simpan file ini sebagai bukti bahwa anda telah melakukan pemilihan yang valid'."\r\n";
            $file .= "\r\n";
            $file .= 'Demi menjamin asas kerahasiaan pemilu pada umumnya, letakkan file ini di lokasi yang aman sehingga tidak ada seorangpun yang bisa melihat pilihan anda'."\r\n";
            $file .= "\r\n";
            $file .= '### DETAIL PILIHAN ###'."\r\n";
            $file .= "\r\n";
            $file .= 'NIM : '.$nim."\r\n";
            $file .= 'ID Capres : '.$idcapres.' ('.$capresModel->find($idcapres)->Nama.')'."\r\n";
            if (($idcaleg !== NULL) && ($idcaleg !== '')) {
                $file .= 'ID Caleg : '.$idcaleg.' ('.$calegModel->find($idcaleg)->Nama.')'."\r\n";
            }
            $file .= 'Timestamp : '.$timestamp."\r\n";
            $file .= "\r\n";
            $file .= '### Tanda Tangan Digital: '.$signature.' ###'."\r\n";
            $file .= "\r\n";

            return $this->response->setStatusCode(200)->setContentType('text/plain')->download('Bukti Pemilihan '.$_ENV['pemira.info.title'].' - '.$nim.'.txt', $file);
        } else {
            $this->session->set('status', new Status('error', 'Anda belum melakukan voting'));
        }

        return redirect()->to('user/home');
    }

    public function getDetailCapres() {
        $this->response->setContentType('application/json');

        $id = $this->request->getGet('id');

        if (isset($id)) {
            $capresModel = model('App\Models\CapresModel');

            $capres = $capresModel->find($id);
            if (isset($capres)) {
                $data = [];
                $data['id'] = $capres->ID;
                $data['nama'] = $capres->Nama;
                $data['visi'] = $capres->Visi;
                $data['misi'] = $capres->Misi;
                $data['metadata'] = $capres->Metadata;
                return $this->response->setStatusCode(200)->setBody(json_encode($data));
            } else {
                return $this->response->setStatusCode(404);
            }
        } else {
            return $this->response->setStatusCode(400);
        }

        return $this->response->setStatusCode(500);
    }

}

