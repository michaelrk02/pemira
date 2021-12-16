<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Prodi as ProdiEntity;
use App\Libraries\Status;

class Prodi extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        return redirect()->to('admin/prodi/view');
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Data Prodi', TRUE);
        echo view('admin/prodi/data');
        echo $this->viewFooter();
    }

    public function add() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(TRUE) !== NULL) {
            return redirect()->to('admin/prodi/view');
        }
    }

    public function edit() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(FALSE) !== NULL) {
            return redirect()->to('admin/prodi/view');
        }
    }

    public function delete() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $prodiModel = model('App\Models\ProdiModel');

        $id = $this->request->getGet('id');
        if (isset($id)) {
            if ($prodiModel->find($id) !== NULL) {
                $prodiModel->delete($id);
                if ($prodiModel->error()['code'] == 0) {
                    $this->session->set('status', new Status('success', 'Berhasil menghapus prodi'));
                } else {
                    $this->session->set('status', new Status('error', 'Gagal menghapus prodi. Pastikan terlebih dahulu supaya tidak ada sesi yang ditempati prodi tersebut'));
                }
            } else {
                $this->session->set('status', new Status('error', 'Prodi tidak ditemukan'));
            }
        }
        return redirect()->to(site_url('admin/prodi/view'));
    }

    public function fetch() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $prodiModel = model('App\Models\ProdiModel');

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = @$this->request->getGet('search')['value'];
        $result = $prodiModel->fetch($draw, $start, $length, $search);

        foreach ($result['data'] as &$data) {
            $obj = $data;

            $actEdit = '<a class="btn" href="'.site_url('admin/prodi/edit').'?id='.$obj->id.'"><i class="fa fa-edit left"></i> EDIT</a>';
            $actDelete = '<a class="btn red" href="'.site_url('admin/prodi/delete').'?id='.$obj->id.'" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash left"></i> DELETE</a>';
            $actViewSesi = '<button type="button" class="waves-effect waves-light btn listSesi" data-id="'.$obj->id.'"><i class="fa fa-calendar left"></i> LIHAT SESI</button>';
            $actAddSesi = '<button type="button" class="waves-effect waves-light btn addSesi" data-id="'.$obj->id.'"><i class="fa fa-calendar-plus left"></i> TAMBAH SESI</button>';
            $actDelSesi = '<button type="button" class="waves-effect waves-light btn delSesi" data-id="'.$obj->id.'"><i class="fa fa-calendar-minus"></i> HAPUS SESI</button>';

            $arr = [
                'id' => $obj->id,
                'nama' => $obj->nama,
                'tindakan' => implode(' ', [$actEdit, $actDelete, '|', $actViewSesi, $actAddSesi, $actDelSesi])
            ];

            $data = $arr;
        }

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($result));
    }

    public function listSesi() {
        $this->response->setContentType('application/json');

        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $id = $this->request->getGet('id');
        $listSesi = [];
        if (isset($id)) {
            $prodiModel = model('App\Models\ProdiModel');
            $listSesi = $prodiModel->viewSesi($id);
        } else {
            $sesiModel = model('App\Models\SesiModel');
            $listSesi = $sesiModel->findAll();
        }

        return $this->response->setStatusCode(200)->setBody(json_encode($listSesi));
    }

    public function addSesi() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $idprodi = $this->request->getGet('idprodi');
        $idsesi = $this->request->getGet('idsesi');
        if (isset($idprodi) && isset($idsesi)) {
            $prodiModel = model('App\Models\ProdiModel');

            if ($prodiModel->findSesi($idprodi, $idsesi) === NULL) {
                $prodiModel->insertSesi($idprodi, $idsesi);
                return $this->response->setStatusCode(200);
            } else {
                return $this->response->setStatusCode(403);
            }
        } else {
            return $this->response->setStatusCode(400);
        }
        return $this->response->setStatusCode(500);
    }

    public function delSesi() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $idprodi = $this->request->getGet('idprodi');
        $idsesi = $this->request->getGet('idsesi');
        if (isset($idprodi) && isset($idsesi)) {
            $prodiModel = model('App\Models\ProdiModel');

            if ($prodiModel->findSesi($idprodi, $idsesi) !== NULL) {
                $prodiModel->deleteSesi($idprodi, $idsesi);
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
        $prodiModel = model('App\Models\ProdiModel');

        $oldID = '';

        $prodi = new ProdiEntity();
        $prodi->ID = '';
        $prodi->Nama = '';
        if (!$createMode) {
            if ($this->request->getGet('id') === NULL) {
                return FALSE;
            }

            $prodi = $prodiModel->find($this->request->getGet('id'));
            if (!isset($prodi)) {
                $this->session->set('status', new Status('error', 'ID prodi tidak valid'));
                return FALSE;
            }
            $oldID = $prodi->ID;
        }

        if ($this->request->getPost('submit') == 1) {
            $rules = [];

            $rules['ID'] = 'required';
            $rules['Nama'] = 'required|max_length[50]';

            $oldID = $prodi->ID;
            $prodi->ID = $this->request->getPost('ID');
            $prodi->Nama = $this->request->getPost('Nama');
            $newID = $prodi->ID;

            if ($this->validate($rules)) {
                if ($createMode) {
                    if ($prodiModel->find($prodi->ID) === NULL) {
                        $prodiModel->insert($prodi);
                        $this->session->set('status', new Status('success', 'Berhasil menambahkan data prodi'));
                        return TRUE;
                    } else {
                        $this->session->set('status', new Status('error', 'Prodi sudah ada sebelumnya'));
                    }
                } else {
                    if ($prodiModel->find($oldID) !== NULL) {
                        $update = FALSE;
                        if ($newID == $oldID) {
                            $update = TRUE;
                        } else {
                            if ($prodiModel->find($prodi->ID) === NULL) {
                                $update = TRUE;
                            } else {
                                $this->session->set('status', new Status('error', 'ID prodi yang baru sudah ada sebelumnya'));
                            }
                        }
                        if ($update) {
                            $prodiModel->update($oldID, $prodi);
                            $this->session->set('status', new Status('success', 'Berhasil memperbarui prodi'));
                            return TRUE;
                        }
                    } else {
                        $this->session->set('status', new Status('error', 'Prodi tidak ditemukan'));
                    }
                }
            } else {
                $this->session->set('status', new Status('error', $this->validator->listErrors()));
            }
        }
        $this->initStatus();

        $action = $createMode ? 'Add' : 'Edit';
        echo $this->viewHeader($action.' Prodi', TRUE);
        echo view('admin/prodi/editor', [
            'prodi' => $prodi,
            'createMode' => $createMode,
            'action' => site_url(uri_string()).(!$createMode ? '?id='.$oldID : ''),
            'oldID' => $oldID
        ]);
        echo $this->viewFooter();
        return NULL;
    }

}

