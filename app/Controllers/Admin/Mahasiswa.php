<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Mahasiswa as MahasiswaEntity;
use App\Libraries\Status;
use App\Libraries\WebToken;

class Mahasiswa extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        helper('text');
    }

    public function index() {
        return redirect()->to('admin/mahasiswa/view');
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $resetToken = WebToken::fromData(['expired' => time() + 1800], [])->toString();

        echo $this->viewHeader('Data Mahasiswa', TRUE);
        echo view('admin/mahasiswa/data', ['resetToken' => $resetToken]);
        echo $this->viewFooter();
    }

    public function add() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(TRUE) !== NULL) {
            return redirect()->to('admin/mahasiswa/view');
        }
    }

    public function edit() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(FALSE) !== NULL) {
            return redirect()->to('admin/mahasiswa/view');
        }
    }

    public function delete() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $mahasiswaModel = model('App\Models\MahasiswaModel');

        $nim = $this->request->getGet('nim');
        if (isset($nim)) {
            if ($mahasiswaModel->find($nim) !== NULL) {
                $mahasiswaModel->delete($nim);
                if ($mahasiswaModel->error()['code'] == 0) {
                    $this->session->set('status', new Status('success', 'Berhasil menghapus mahasiswa'));
                } else {
                    $this->session->set('status', new Status('error', 'Gagal menghapus mahasiswa'));
                }
            } else {
                $this->session->set('status', new Status('error', 'Mahasiswa tidak ditemukan'));
            }
        }
        return redirect()->to(site_url('admin/mahasiswa/view'));
    }

    public function import() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->request->getPost('submit') == 1) {
            $file = $this->request->getFile('source');

            if ($file->isValid()) {
                $count = 0;
                $saved = 0;
                $succeeded = FALSE;

                $stream = fopen($file->getTempName(), 'r');
                if ($stream !== FALSE) {
                    $delimiter = ',';
                    $header = fgetcsv($stream, NULL, $delimiter);
                    if (count($header) < 4) {
                        fseek($stream, 0, SEEK_SET);
                        $delimiter = ';';
                        $header = fgetcsv($stream, NULL, $delimiter);
                    }
                    if (count($header) >= 4) {
                        $mapping = [];
                        foreach ($header as $i => $h) {
                            if (strtolower(trim($h)) === 'nim') { $mapping['NIM'] = $i; }
                            if (strtolower(trim($h)) === 'nama') { $mapping['Nama'] = $i; }
                            if (strtolower(trim($h)) === 'idprodi') { $mapping['IDProdi'] = $i; }
                            if (strtolower(trim($h)) === 'angkatan') { $mapping['Angkatan'] = $i; }
                        }
                        if (isset($mapping['NIM']) && isset($mapping['Nama']) && isset($mapping['IDProdi']) && isset($mapping['Angkatan'])) {
                            $mahasiswaModel = model('App\Models\MahasiswaModel');

                            while (($entry = fgetcsv($stream, NULL, $delimiter)) !== FALSE) {
                                if (count($entry) >= 4) {
                                    $mahasiswa = new MahasiswaEntity();
                                    $mahasiswa->NIM = $entry[$mapping['NIM']];
                                    $mahasiswa->Nama = $entry[$mapping['Nama']];
                                    $mahasiswa->IDProdi = $entry[$mapping['IDProdi']];
                                    $mahasiswa->Angkatan = $entry[$mapping['Angkatan']];
                                    $mahasiswa->SSO = NULL;
                                    if ($mahasiswaModel->find($mahasiswa->NIM) === NULL) {
                                        $mahasiswaModel->insert($mahasiswa);
                                    } else {
                                        $mahasiswaModel->update($mahasiswa->NIM, $mahasiswa);
                                    }

                                    if ($mahasiswaModel->error()['code'] == 0) {
                                        $saved++;
                                    }

                                    $count++;
                                }
                            }

                            $succeeded = TRUE;
                        }
                    }

                    fclose($stream);
                }

                if ($succeeded) {
                    $this->session->set('status', new Status('success', 'Berhasil memasukkan '.$saved.' dari '.$count.' data mahasiswa'));
                    return redirect()->to(site_url('admin/mahasiswa/view'));
                } else {
                    $this->session->set('status', new Status('error', 'Gagal membaca file CSV yang telah diunggah'));
                }
            } else {
                $this->session->set('status', new Status('error', 'Gagal mengunggah file CSV'));
            }
        }
        $this->initStatus();

        echo $this->viewHeader('Import Mahasiswa', TRUE);
        echo view('admin/mahasiswa/import');
        echo $this->viewFooter();
    }

    public function reset() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $token = WebToken::fromString($this->request->getGet('token'));
        if (isset($token)) {
            $mahasiswaModel = model('App\Models\MahasiswaModel');
            $mahasiswaModel->builder()->emptyTable();
            $this->session->set('status', new Status('success', 'Berhasil mereset mahasiswa'));
        } else {
            $this->session->set('status', new Status('error', 'Token reset tidak valid. Silakan refresh halaman ini kemudian lakukan reset lagi'));
        }
        return redirect()->to('admin/mahasiswa/view');
    }

    public function scan() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $this->initStatus();
        echo $this->viewHeader('Scan Kode Akses', TRUE);
        echo view('admin/mahasiswa/scan');
        echo $this->viewFooter();
    }

    public function verifikasi() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $mahasiswaModel = model('App\Models\MahasiswaModel');
        $prodiModel = model('App\Models\ProdiModel');

        $token = $this->request->getGet('token');
        $token = WebToken::fromString($token);

        if (!isset($token)) {
            die('Invalid token');
        }

        $nim = $token->payload['nim'];
        $session = $token->payload['session'];

        $mhs = $mahasiswaModel->find($token->payload['nim']);
        if (!isset($mhs)) {
            die('Mahasiswa dengan NIM '.$token->payload['nim'].' tidak ditemukan');
        }

        $mhs->Prodi = $prodiModel->find($mhs->IDProdi)->nama;

        if ($this->request->getPost('submit') == 1) {
            $mhs->QRSessionID = $session;
            $mahasiswaModel->update($nim, $mhs);
            $this->session->set('status', new Status('success', 'Berhasil melakukan verifikasi mahasiswa. Anda dapat menutup halaman ini'));
        }

        $this->initStatus();
        echo $this->viewHeader('Verifikasi Mahasiswa', TRUE);
        echo view('admin/mahasiswa/verifikasi', ['mhs' => $mhs, 'session' => $token->payload['session'], 'token' => $this->request->getGet('token')]);
        echo $this->viewFooter();
    }

    public function code() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $mahasiswaModel = model('App\Models\MahasiswaModel');
        $prodiModel = model('App\Models\ProdiModel');

        $nim = $this->request->getGet('nim');
        $mhs = $mahasiswaModel->find($nim);
        $mhs->Prodi = $prodiModel->find($mhs->IDProdi)->Nama;

        $code = NULL;
        if ($this->request->getPost('submit') == 1) {
            $code = random_string('numeric', 6);

            $mhs->KodeAkses = sha1($code);
            $mhs->KodeAksesExpire = date('Y-m-d H:i:s', time() + 3600);
            $mahasiswaModel->update($nim, $mhs);
        }

        $this->initStatus();
        echo $this->viewHeader('Generate Kode Akses', TRUE);
        echo view('admin/mahasiswa/code', ['mhs' => $mhs, 'code' => $code]);
        echo $this->viewFooter();
    }

    public function fetch() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $mahasiswaModel = model('App\Models\MahasiswaModel');
        $pemilihModel = model('App\Models\PemilihModel');

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = @$this->request->getGet('search')['value'];
        $result = $mahasiswaModel->fetch($draw, $start, $length, $search);

        foreach ($result['data'] as &$data) {
            $obj = $data;

            $mhs = new MahasiswaEntity();
            $mhs->NIM = $obj->nim;
            $mhs->Nama = $obj->nama;
            $mhs->IDProdi = $obj->idprodi;
            $mhs->Angkatan = $obj->angkatan;
            $mhs->SSO = $obj->sso;

            $actCode = '<a class="btn" href="'.site_url('admin/mahasiswa/code').'?nim='.$obj->nim.'" target="_blank"><i class="fa fa-lock left"></i> KODE</a>';
            $actEdit = '<a class="btn" href="'.site_url('admin/mahasiswa/edit').'?nim='.$obj->nim.'"><i class="fa fa-edit left"></i> EDIT</a>';
            $actDelete = '<a class="btn red" href="'.site_url('admin/mahasiswa/delete').'?nim='.$obj->nim.'" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash left"></i> DELETE</a>';

            $arr = [
                'nim' => $obj->nim,
                'nama' => $obj->nama,
                'prodi' => $obj->prodi,
                'angkatan' => $obj->angkatan,
                'sso_aktif' => (($obj->sso !== NULL) && ($obj->sso !== '')) ? '<span><i class="fa fa-check green-text"></i> Yes</span>' : '<span><i class="fa fa-times red-text"></i> No</span>',
                'sudah_memilih' => ($pemilihModel->find($mhs->getToken()) !== NULL) ? '<span><i class="fa fa-check green-text"></i> Yes</span>' : '<span><i class="fa fa-times red-text"></i> No</span>',
                'tindakan' => implode(' ', [$actCode, $actEdit, $actDelete])
            ];

            $data = $arr;
        }

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($result));
    }

    protected function initEditor($createMode) {
        $mahasiswaModel = model('App\Models\MahasiswaModel');
        $prodiModel = model('App\Models\ProdiModel');

        $oldNIM = '';

        $mahasiswa = new MahasiswaEntity();
        $mahasiswa->NIM = '';
        $mahasiswa->Nama = '';
        $mahasiswa->Angkatan = '';
        $mahasiswa->SSO = NULL;
        $mahasiswa->IDProdi = '';
        if (!$createMode) {
            if ($this->request->getGet('nim') === NULL) {
                return FALSE;
            }

            $mahasiswa = $mahasiswaModel->find($this->request->getGet('nim'));
            if (!isset($mahasiswa)) {
                $this->session->set('status', new Status('error', 'NIM mahasiswa tidak valid'));
                return FALSE;
            }
            $oldNIM = $mahasiswa->NIM;
        }

        if ($this->request->getPost('submit') == 1) {
            $rules = [];

            $rules['NIM'] = 'required';
            $rules['Nama'] = 'required|max_length[100]';
            $rules['Angkatan'] = 'required';
            $rules['IDProdi'] = 'required';

            $oldNIM = $mahasiswa->NIM;
            $mahasiswa->NIM = $this->request->getPost('NIM');
            $mahasiswa->Nama = $this->request->getPost('Nama');
            $mahasiswa->Angkatan = $this->request->getPost('Angkatan');
            $mahasiswa->IDProdi = $this->request->getPost('IDProdi');
            $mahasiswa->SSO = $this->request->getPost('SSO');
            $newNIM = $mahasiswa->NIM;

            if ($this->validate($rules)) {
                if ($createMode) {
                    if ($mahasiswaModel->find($mahasiswa->NIM) === NULL) {
                        $mahasiswaModel->insert($mahasiswa);
                        $this->session->set('status', new Status('success', 'Berhasil menambahkan data mahasiswa'));
                        return TRUE;
                    } else {
                        $this->session->set('status', new Status('error', 'Mahasiswa sudah ada sebelumnya'));
                    }
                } else {
                    if ($mahasiswaModel->find($oldNIM) !== NULL) {
                        $update = FALSE;
                        if ($newNIM == $oldNIM) {
                            $update = TRUE;
                        } else {
                            if ($mahasiswaModel->find($mahasiswa->NIM) === NULL) {
                                $update = TRUE;
                            } else {
                                $this->session->set('status', new Status('error', 'NIM mahasiswa yang baru sudah ada sebelumnya'));
                            }
                        }
                        if ($update) {
                            $mahasiswaModel->update($oldNIM, $mahasiswa);
                            $this->session->set('status', new Status('success', 'Berhasil memperbarui mahasiswa'));
                            return TRUE;
                        }
                    } else {
                        $this->session->set('status', new Status('error', 'Mahasiswa tidak ditemukan'));
                    }
                }
            } else {
                $this->session->set('status', new Status('error', $this->validator->listErrors()));
            }
        }
        $this->initStatus();

        $action = $createMode ? 'Add' : 'Edit';
        echo $this->viewHeader($action.' Mahasiswa', TRUE);
        echo view('admin/mahasiswa/editor', [
            'mahasiswa' => $mahasiswa,
            'createMode' => $createMode,
            'action' => site_url(uri_string()).(!$createMode ? '?nim='.$oldNIM : ''),
            'oldNIM' => $oldNIM,
            'listProdi' => $prodiModel->findAll()
        ]);
        echo $this->viewFooter();
        return NULL;
    }

}

