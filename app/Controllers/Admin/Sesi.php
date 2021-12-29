<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Sesi as SesiEntity;
use App\Libraries\Status;

class Sesi extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        return redirect()->to('admin/sesi/view');
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Data Sesi', TRUE);
        echo view('admin/sesi/data');
        echo $this->viewFooter();
    }

    public function add() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(TRUE) !== NULL) {
            return redirect()->to('admin/sesi/view');
        }
    }

    public function edit() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(FALSE) !== NULL) {
            return redirect()->to('admin/sesi/view');
        }
    }

    public function delete() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $sesiModel = model('App\Models\SesiModel');

        $id = $this->request->getGet('id');
        if (isset($id)) {
            if ($sesiModel->find($id) !== NULL) {
                $sesiModel->delete($id);
                if ($sesiModel->error()['code'] == 0) {
                    $this->session->set('status', new Status('success', 'Berhasil menghapus sesi'));
                } else {
                    $this->session->set('status', new Status('error', 'Gagal menghapus sesi'));
                }
            } else {
                $this->session->set('status', new Status('error', 'Sesi tidak ditemukan'));
            }
        }
        return redirect()->to(site_url('admin/sesi/view'));
    }

    public function fetch() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $sesiModel = model('App\Models\SesiModel');

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = @$this->request->getGet('search')['value'];
        $result = $sesiModel->fetch($draw, $start, $length, $search);

        foreach ($result['data'] as &$data) {
            $obj = $data;

            $actEdit = '<a class="btn" href="'.site_url('admin/sesi/edit').'?id='.$obj->id.'"><i class="fa fa-edit left"></i> EDIT</a>';
            $actDelete = '<a class="btn red" href="'.site_url('admin/sesi/delete').'?id='.$obj->id.'" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash left"></i> DELETE</a>';
            $actListJadwal = '<button type="button" class="waves-effect waves-light btn listJadwal" data-id="'.$obj->id.'"><i class="fa fa-list left"></i> LIHAT JADWAL</button>';
            $actAddJadwal = '<button type="button" class="waves-effect waves-light btn addJadwal" data-id="'.$obj->id.'" data-nama="'.esc($obj->nama).'"><i class="fa fa-calendar-plus left"></i> TAMBAH JADWAL</button>';
            $actDelJadwal = '<button type="button" class="waves-effect waves-light btn delJadwal" data-id="'.$obj->id.'" data-nama="'.esc($obj->nama).'"><i class="fa fa-calendar-minus"></i> HAPUS JADWAL</button>';

            $arr = [
                'id' => $obj->id,
                'nama' => $obj->nama,
                'waktu_buka' => date('Y-m-d H:i', $obj->waktu_buka),
                'waktu_tutup' => date('Y-m-d H:i', $obj->waktu_tutup),
                'tindakan' => implode(' ', [$actEdit, $actDelete, '|', $actListJadwal, $actAddJadwal, $actDelJadwal])
            ];

            $data = $arr;
        }

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($result));
    }

    public function listProdi() {
        $this->response->setContentType('application/json');

        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $id = $this->request->getGet('id');
        $action = $this->request->getGet('action');
        $listProdi = [];
        if (isset($id) && isset($action)) {
            $sesiModel = model('App\Models\SesiModel');
            if ($action === 'add') {
                $listProdi = $sesiModel->viewProdi($id, TRUE);
            } else if ($action === 'del') {
                $listProdi = $sesiModel->viewProdi($id, FALSE);
            }
        } else {
            return $this->response->setStatusCode(400);
        }

        return $this->response->setStatusCode(200)->setBody(json_encode($listProdi));
    }

    public function listJadwal() {
        $sesiModel = model('App\Models\SesiModel');

        $id = $this->request->getGet('id');
        if (isset($id)) {
            $viewProdi = $sesiModel->viewProdi($id);

            echo '<p>Prodi yang terjadwal:</p>';
            echo '<ul class="browser-default">';
            foreach ($viewProdi as $prodi) {
                echo ' <li>'.htmlspecialchars($prodi->prodi_nama).'</li>';
            }
            echo '</ul>';
        } else {
            echo 'Parameter tidak valid';
        }
    }

    public function addJadwal() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $idsesi = $this->request->getGet('idsesi');
        $idprodi = $this->request->getGet('idprodi');
        if (isset($idsesi) && isset($idprodi)) {
            $sesiModel = model('App\Models\SesiModel');

            if ($sesiModel->findJadwal($idsesi, $idprodi) === NULL) {
                $sesiModel->insertJadwal($idsesi, $idprodi);
                return $this->response->setStatusCode(200);
            } else {
                return $this->response->setStatusCode(400);
            }
        } else {
            return $this->response->setStatusCode(400);
        }
        return $this->response->setStatusCode(500);
    }

    public function delJadwal() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $idsesi = $this->request->getGet('idsesi');
        $idprodi = $this->request->getGet('idprodi');
        if (isset($idsesi) && isset($idprodi)) {
            $sesiModel = model('App\Models\SesiModel');

            if ($sesiModel->findJadwal($idsesi, $idprodi) !== NULL) {
                $sesiModel->deleteJadwal($idsesi, $idprodi);
                return $this->response->setStatusCode(200);
            } else {
                return $this->response->setStatusCode(404);
            }
        } else {
            return $this->response->setStatusCode(400);
        }
        return $this->response->setStatusCode(500);
    }

    protected function initEditor($createMode) {
        $sesiModel = model('App\Models\SesiModel');

        $oldID = '';

        $sesi = new SesiEntity();
        $sesi->ID = '';
        $sesi->Nama = '';
        $sesi->WaktuBuka = time();
        $sesi->WaktuTutup = time();
        if (!$createMode) {
            if ($this->request->getGet('id') === NULL) {
                return FALSE;
            }

            $sesi = $sesiModel->find($this->request->getGet('id'));
            if (!isset($sesi)) {
                $this->session->set('status', new Status('error', 'ID sesi tidak valid'));
                return FALSE;
            }
            $oldID = $sesi->ID;
        }

        if ($this->request->getPost('submit') == 1) {
            $rules = [];

            $rules['ID'] = 'required';
            $rules['Nama'] = 'required|max_length[100]';
            $rules['WaktuBukaDate'] = 'required';
            $rules['WaktuBukaTime'] = 'required';
            $rules['WaktuTutupDate'] = 'required';
            $rules['WaktuTutupTime'] = 'required';

            $oldID = $sesi->ID;
            $sesi->ID = $this->request->getPost('ID');
            $sesi->Nama = $this->request->getPost('Nama');
            $sesi->setWaktuBukaString($this->request->getPost('WaktuBukaDate').' '.$this->request->getPost('WaktuBukaTime'));
            $sesi->setWaktuTutupString($this->request->getPost('WaktuTutupDate').' '.$this->request->getPost('WaktuTutupTime'));
            $newID = $sesi->ID;

            if ($this->validate($rules)) {
                if ($createMode) {
                    if ($sesiModel->find($sesi->ID) === NULL) {
                        $sesiModel->insert($sesi);
                        $this->session->set('status', new Status('success', 'Berhasil menambahkan data sesi'));
                        return TRUE;
                    } else {
                        $this->session->set('status', new Status('error', 'Sesi sudah ada sebelumnya'));
                    }
                } else {
                    if ($sesiModel->find($oldID) !== NULL) {
                        $update = FALSE;
                        if ($newID == $oldID) {
                            $update = TRUE;
                        } else {
                            if ($sesiModel->find($sesi->ID) === NULL) {
                                $update = TRUE;
                            } else {
                                $this->session->set('status', new Status('error', 'ID sesi yang baru sudah ada sebelumnya'));
                            }
                        }
                        if ($update) {
                            $sesiModel->update($oldID, $sesi);
                            $this->session->set('status', new Status('success', 'Berhasil memperbarui sesi'));
                            return TRUE;
                        }
                    } else {
                        $this->session->set('status', new Status('error', 'Sesi tidak ditemukan'));
                    }
                }
            } else {
                $this->session->set('status', new Status('error', $this->validator->listErrors()));
            }
        }
        $this->initStatus();

        $action = $createMode ? 'Add' : 'Edit';
        echo $this->viewHeader($action.' Sesi', TRUE);
        echo view('admin/sesi/editor', [
            'sesi' => $sesi,
            'createMode' => $createMode,
            'action' => site_url(uri_string()).(!$createMode ? '?id='.$oldID : ''),
            'oldID' => $oldID
        ]);
        echo $this->viewFooter();
        return NULL;
    }

}

