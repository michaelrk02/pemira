<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Partai as PartaiEntity;
use App\Libraries\Status;

class Partai extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        return redirect()->to('admin/partai/view');
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Data Partai', TRUE);
        echo view('admin/partai/data');
        echo $this->viewFooter();
    }

    public function add() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(TRUE) !== NULL) {
            return redirect()->to('admin/partai/view');
        }
    }

    public function edit() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(FALSE) !== NULL) {
            return redirect()->to('admin/partai/view');
        }
    }

    public function delete() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $partaiModel = model('App\Models\PartaiModel');

        $id = $this->request->getGet('id');
        if (isset($id)) {
            if ($partaiModel->find($id) !== NULL) {
                $partaiModel->delete($id);
                if ($partaiModel->error()['code'] == 0) {
                    $this->session->set('status', new Status('success', 'Berhasil menghapus partai'));
                } else {
                    $this->session->set('status', new Status('error', 'Gagal menghapus partai, kemungkinan masih terdapat data pemilih atau suara yang terkait dengan partai tersebut'));
                }
            } else {
                $this->session->set('status', new Status('error', 'Partai tidak ditemukan'));
            }
        }
        return redirect()->to(site_url('admin/partai/view'));
    }

    public function fetch() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $partaiModel = model('App\Models\PartaiModel');

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = @$this->request->getGet('search')['value'];
        $result = $partaiModel->fetch($draw, $start, $length, $search);

        foreach ($result['data'] as &$data) {
            $obj = $data;

            $actEdit = '<a class="btn" href="'.site_url('admin/partai/edit').'?id='.$obj->id.'"><i class="fa fa-edit left"></i> EDIT</a>';
            $actDelete = '<a class="btn red" href="'.site_url('admin/partai/delete').'?id='.$obj->id.'" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash left"></i> DELETE</a>';

            $arr = [
                'id' => $obj->id,
                'nama' => $obj->nama,
                'tindakan' => implode(' ', [$actEdit, $actDelete])
            ];

            $data = $arr;
        }

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($result));
    }

    protected function initEditor($createMode) {
        $partaiModel = model('App\Models\PartaiModel');

        $oldID = '';

        $partai = new PartaiEntity();
        $partai->ID = '';
        $partai->Nama = '';
        $partai->IDFoto = '';
        if (!$createMode) {
            if ($this->request->getGet('id') === NULL) {
                return FALSE;
            }

            $partai = $partaiModel->find($this->request->getGet('id'));
            if (!isset($partai)) {
                $this->session->set('status', new Status('error', 'ID partai tidak valid'));
                return FALSE;
            }
            $oldID = $partai->ID;
        }

        if ($this->request->getPost('submit') == 1) {
            $rules = [];

            $rules['ID'] = 'required';
            $rules['Nama'] = 'required|max_length[200]';

            $oldID = $partai->ID;
            $partai->ID = $this->request->getPost('ID');
            $partai->Nama = $this->request->getPost('Nama');
            $partai->IDFoto = $this->request->getPost('IDFoto');
            $newID = $partai->ID;

            if ($this->validate($rules)) {
                if ($createMode) {
                    if ($partaiModel->find($partai->ID) === NULL) {
                        $partaiModel->insert($partai);
                        $this->session->set('status', new Status('success', 'Berhasil menambahkan data partai'));
                        return TRUE;
                    } else {
                        $this->session->set('status', new Status('error', 'Partai sudah ada sebelumnya'));
                    }
                } else {
                    if ($partaiModel->find($oldID) !== NULL) {
                        $update = FALSE;
                        if ($newID == $oldID) {
                            $update = TRUE;
                        } else {
                            if ($partaiModel->find($partai->ID) === NULL) {
                                $update = TRUE;
                            } else {
                                $this->session->set('status', new Status('error', 'ID partai yang baru sudah ada sebelumnya'));
                            }
                        }
                        if ($update) {
                            $partaiModel->update($oldID, $partai);
                            $this->session->set('status', new Status('success', 'Berhasil memperbarui partai'));
                            return TRUE;
                        }
                    } else {
                        $this->session->set('status', new Status('error', 'Partai tidak ditemukan'));
                    }
                }
            } else {
                $this->session->set('status', new Status('error', $this->validator->listErrors()));
            }
        }
        $this->initStatus();

        $action = $createMode ? 'Add' : 'Edit';
        echo $this->viewHeader($action.' Partai', TRUE);
        echo view('admin/partai/editor', [
            'partai' => $partai,
            'createMode' => $createMode,
            'action' => site_url(uri_string()).(!$createMode ? '?id='.$oldID : ''),
            'oldID' => $oldID
        ]);
        echo $this->viewFooter();
        return NULL;
    }

}

