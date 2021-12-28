<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Caleg as CalegEntity;
use App\Libraries\Status;

class Caleg extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        return redirect()->to('admin/caleg/view');
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Data Caleg', TRUE);
        echo view('admin/caleg/data');
        echo $this->viewFooter();
    }

    public function add() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(TRUE) !== NULL) {
            return redirect()->to('admin/caleg/view');
        }
    }

    public function edit() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(FALSE) !== NULL) {
            return redirect()->to('admin/caleg/view');
        }
    }

    public function delete() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $calegModel = model('App\Models\CalegModel');

        $id = $this->request->getGet('id');
        if (isset($id)) {
            if ($calegModel->find($id) !== NULL) {
                $calegModel->delete($id);
                if ($calegModel->error()['code'] == 0) {
                    $this->session->set('status', new Status('success', 'Berhasil menghapus caleg'));
                } else {
                    $this->session->set('status', new Status('error', 'Gagal menghapus caleg, kemungkinan masih terdapat data pemilih atau suara yang terkait dengan caleg tersebut'));
                }
            } else {
                $this->session->set('status', new Status('error', 'Caleg tidak ditemukan'));
            }
        }
        return redirect()->to(site_url('admin/caleg/view'));
    }

    public function fetch() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $calegModel = model('App\Models\CalegModel');

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = @$this->request->getGet('search')['value'];
        $result = $calegModel->fetch($draw, $start, $length, $search);

        foreach ($result['data'] as &$data) {
            $obj = $data;

            $actEdit = '<a class="btn" href="'.site_url('admin/caleg/edit').'?id='.$obj->id.'"><i class="fa fa-edit left"></i> EDIT</a>';
            $actDelete = '<a class="btn red" href="'.site_url('admin/caleg/delete').'?id='.$obj->id.'" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash left"></i> DELETE</a>';

            $arr = [
                'id' => $obj->id,
                'nama' => $obj->nama,
                'prodi' => ($obj->prodi === NULL) || ($obj->prodi === '') ? '(umum)' : $obj->prodi,
                'tindakan' => implode(' ', [$actEdit, $actDelete])
            ];

            $data = $arr;
        }

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($result));
    }

    protected function initEditor($createMode) {
        $calegModel = model('App\Models\CalegModel');
        $prodiModel = model('App\Models\ProdiModel');

        $oldID = '';

        $caleg = new CalegEntity();
        $caleg->ID = '';
        $caleg->Nama = '';
        $caleg->IDProdi = '';
        $caleg->IDFoto = '';
        if (!$createMode) {
            if ($this->request->getGet('id') === NULL) {
                return FALSE;
            }

            $caleg = $calegModel->find($this->request->getGet('id'));
            if (!isset($caleg)) {
                $this->session->set('status', new Status('error', 'ID caleg tidak valid'));
                return FALSE;
            }
            $oldID = $caleg->ID;
        }

        if ($this->request->getPost('submit') == 1) {
            $rules = [];

            $rules['ID'] = 'required';
            $rules['Nama'] = 'required|max_length[200]';
            $rules['IDProdi'] = 'required';

            $oldID = $caleg->ID;
            $caleg->ID = $this->request->getPost('ID');
            $caleg->Nama = $this->request->getPost('Nama');
            $caleg->IDProdi = $this->request->getPost('IDProdi');
            $caleg->IDProdi = $caleg->IDProdi == 0 ? NULL : $caleg->IDProdi;
            $caleg->IDFoto = $this->request->getPost('IDFoto');
            $newID = $caleg->ID;

            if ($this->validate($rules)) {
                if ($createMode) {
                    if ($calegModel->find($caleg->ID) === NULL) {
                        $calegModel->insert($caleg);
                        $this->session->set('status', new Status('success', 'Berhasil menambahkan data caleg'));
                        return TRUE;
                    } else {
                        $this->session->set('status', new Status('error', 'Caleg sudah ada sebelumnya'));
                    }
                } else {
                    if ($calegModel->find($oldID) !== NULL) {
                        $update = FALSE;
                        if ($newID == $oldID) {
                            $update = TRUE;
                        } else {
                            if ($calegModel->find($caleg->ID) === NULL) {
                                $update = TRUE;
                            } else {
                                $this->session->set('status', new Status('error', 'ID caleg yang baru sudah ada sebelumnya'));
                            }
                        }
                        if ($update) {
                            $calegModel->update($oldID, $caleg);
                            $this->session->set('status', new Status('success', 'Berhasil memperbarui caleg'));
                            return TRUE;
                        }
                    } else {
                        $this->session->set('status', new Status('error', 'Caleg tidak ditemukan'));
                    }
                }
            } else {
                $this->session->set('status', new Status('error', $this->validator->listErrors()));
            }
        }
        $this->initStatus();

        $action = $createMode ? 'Add' : 'Edit';
        echo $this->viewHeader($action.' Caleg', TRUE);
        echo view('admin/caleg/editor', [
            'caleg' => $caleg,
            'createMode' => $createMode,
            'action' => site_url(uri_string()).(!$createMode ? '?id='.$oldID : ''),
            'oldID' => $oldID,
            'listProdi' => $prodiModel->findAll()
        ]);
        echo $this->viewFooter();
        return NULL;
    }

}

