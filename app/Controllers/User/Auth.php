<?php

namespace App\Controllers\User;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\UserController;
use App\Libraries\ActivationMessage;
use App\Libraries\Status;
use App\Libraries\WebToken;

class Auth extends UserController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        helper([
            'censor',
            'form'
        ]);
    }

    public function login() {
        if ($this->userLogin !== NULL) {
            return redirect()->to('user/home');
        }

        if ($this->request->getPost('submit') == 1) {
            $file = $this->request->getFile('idcard');

            if ($file->isValid()) {
                $mahasiswaModel = model('App\Models\MahasiswaModel');

                $data = file_get_contents($file->getTempName());
                $data = @base64_decode($data);
                $data = @json_decode($data, TRUE);

                $nim = @$data['nim'];
                $token = @$data['token'];

                $mhs = $mahasiswaModel->find($nim);
                if (isset($mhs)) {
                    if (!empty($nim) && ($mhs->SSO !== NULL) && ($token === $mhs->getToken())) {
                        $this->session->set('user_login', $nim);
                        $this->session->set('status', new Status('success', 'Selamat datang, '.esc($mhs->Nama).'!'));
                        return redirect()->to('user/home');
                    } else {
                        $this->session->set('status', new Status('error', 'Kartu akses tidak valid. Silakan untuk melakukan aktivasi sesuai identitas anda'));
                    }
                } else {
                    $this->session->set('status', new Status('error', 'Mahasiswa tidak ditemukan'));
                }
            } else {
                $this->session->set('status', new Status('error', 'Gagal mengunggah kartu akses'));
            }
        } else if ($this->request->getPost('submit') == 2) {
            $mahasiswaModel = model('App\Models\MahasiswaModel');

            $nim = $this->request->getPost('nim');
            $sso = $this->request->getPost('sso');

            if (!empty($sso)) {
                $mhsByNIM = $mahasiswaModel->find($nim);
                $mhsBySSO = $mahasiswaModel->findBySSO($sso);

                $status = $this->getActivationStatus($mhsByNIM, $mhsBySSO);
                if ($status->severity === 'success') {
                    $token = WebToken::fromData(['expired' => time() + $_ENV['pemira.activation.expire']], ['nim' => $nim, 'sso' => $sso]);

                    $message = new ActivationMessage($nim, censor($mhsByNIM->Nama), $sso, $token->toString());

                    $email = \Config\Services::email();
                    $email->setFrom($_ENV['pemira.mail.sender']);
                    $email->setTo($sso.'@'.$_ENV['pemira.mail.host']);
                    $email->setSubject('Aktivasi Akun - '.$_ENV['pemira.info.title']);
                    $email->setMessage($message->getFullHTML());
                    $email->send();

                    $status = new Status('success', 'Link aktivasi NIM '.esc($nim).' ('.censor($mhsByNIM->Nama).') telah dikirim ke '.esc($sso.'@'.$_ENV['pemira.mail.host']).'. Silakan cek pada inbox + spam email SSO tersebut');
                }
                $this->session->set('status', $status);
                if ($status->severity === 'success') {
                    return redirect()->to('user/auth/login');
                }
            } else {
                $this->session->set('status', new Status('error', 'Harap untuk menginput username SSO terlebih dahulu'));
            }
        }
        $this->initStatus();

        echo $this->viewHeader('Masuk');
        echo view('user/auth/login');
        echo $this->viewFooter();
    }

    public function logout() {
        $this->session->remove('user_login');
        return redirect()->to('user/home');
    }

    public function activate() {
        if ($this->userLogin !== NULL) {
            return redirect()->to('user/home');
        }

        $token = WebToken::fromString($this->request->getGet('token') ?? '');
        if (isset($token)) {
            $mahasiswaModel = model('App\Models\MahasiswaModel');

            $nim = $token->payload['nim'];
            $sso = $token->payload['sso'];

            $mhsByNIM = $mahasiswaModel->find($nim);
            $mhsBySSO = $mahasiswaModel->findBySSO($sso);

            $status = $this->getActivationStatus($mhsByNIM, $mhsBySSO);
            if ($status->severity === 'success') {
                if ($mhsByNIM->SSO !== $sso) {
                    $mhsByNIM->SSO = $sso;
                    $mahasiswaModel->update($nim, $mhsByNIM);
                }

                echo $this->viewHeader('Aktivasi Akun');
                echo view('user/auth/activate', [
                    'mhs' => $mahasiswaModel->viewFull($nim),
                    'accessCard' => site_url('user/auth/accesscard').'?token='.urlencode($token->toString())
                ]);
                echo $this->viewFooter();
                return;
            } else {
                $this->session->set('status', $status);
            }
        } else {
            $this->session->set('status', new Status('error', 'Maaf! Token pada URL tidak valid'));
        }

        return redirect()->to('user/auth/login');
    }

    public function checkStatus() {
        $mahasiswaModel = model('App\Models\MahasiswaModel');

        $nim = $this->request->getGet('nim') ?? '';
        $sso = $this->request->getGet('sso') ?? '';

        $mhsByNIM = $mahasiswaModel->find($nim);
        $mhsBySSO = $mahasiswaModel->findBySSO($sso);

        echo $this->getActivationStatus($mhsByNIM, $mhsBySSO)->message;
    }

    public function accessCard() {
        $this->response->setContentType('text/plain');

        $token = WebToken::fromString($this->request->getGet('token') ?? '');
        if (isset($token)) {
            $mahasiswaModel = model('App\Models\MahasiswaModel');

            $mhs = $mahasiswaModel->find($token->payload['nim']);
            if (isset($mhs)) {
                $this->response->setStatusCode(200);

                $data = [
                    'nim' => $token->payload['nim'],
                    'token' => $mhs->getToken()
                ];
                $data = json_encode($data);
                $data = base64_encode($data);

                return $this->response->download('IDPEMIRA_'.$token->payload['nim'].'.idc', $data);
            } else {
                return $this->response->setStatusCode(404, 'Mahasiswa tidak ditemukan');
            }
        } else {
            return $this->response->setStatusCode(403, 'Token pada URL tidak valid');
        }
        return $this->response->setStatusCode(500);
    }

    protected function getActivationStatus($mhsByNIM, $mhsBySSO) {
        if (isset($mhsByNIM)) {
            $nim = $mhsByNIM->NIM;
            if (isset($mhsBySSO)) {
                if ($mhsByNIM->NIM === $mhsBySSO->NIM) {
                    return new Status('success', 'Mahasiswa dengan NIM '.esc($nim).' ('.censor($mhsByNIM->Nama).') sudah melakukan aktivasi akun menggunakan username SSO tersebut. Silakan untuk masuk menggunakan kartu akses yang sudah dikirim atau klik pada AKTIVASI lagi apabila kartu akses hilang');
                } else {
                    return new Status('error', 'Mahasiswa dengan NIM '.esc($nim).' ('.censor($mhsByNIM->Nama).') telah melakukan aktivasi dengan username SSO lain ('.esc(censor($mhsByNIM->SSO).'@'.$_ENV['pemira.mail.host']).'). Silakan untuk melapor ke contact person apabila hal ini merupakan kejanggalan');
                }
            } else {
                return new Status('success', 'Mahasiswa dengan NIM '.esc($nim).' ('.censor($mhsByNIM->Nama).') belum melakukan aktivasi akun. Silakan untuk melakukan aktivasi menggunakan username SSO yang anda inputkan jika benar');
            }
        } else {
            return new Status('error', 'Mahasiswa dengan NIM tersebut tidak ditemukan');
        }
        return new Status('error', 'Terjadi kesalahan dalam sistem');
    }

}

