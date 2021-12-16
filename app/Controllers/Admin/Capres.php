<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Entities\Capres as CapresEntity;
use App\Libraries\Status;

class Capres extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        return redirect()->to('admin/capres/view');
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Data Capres', TRUE);
        echo view('admin/capres/data');
        echo $this->viewFooter();
    }

    public function add() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(TRUE) !== NULL) {
            return redirect()->to('admin/capres/view');
        }
    }

    public function edit() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->initEditor(FALSE) !== NULL) {
            return redirect()->to('admin/capres/view');
        }
    }

    public function delete() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $capresModel = model('App\Models\CapresModel');

        $id = $this->request->getGet('id');
        if (isset($id)) {
            if ($capresModel->find($id) !== NULL) {
                $capresModel->delete($id);
                if ($capresModel->error()['code'] == 0) {
                    $this->session->set('status', new Status('success', 'Berhasil menghapus capres'));
                } else {
                    $this->session->set('status', new Status('error', 'Gagal menghapus capres, kemungkinan masih terdapat data pemilih atau suara yang terkait dengan capres tersebut'));
                }
            } else {
                $this->session->set('status', new Status('error', 'Capres tidak ditemukan'));
            }
        }
        return redirect()->to(site_url('admin/capres/view'));
    }

    public function fetch() {
        if (!$this->adminLogin) {
            return $this->response->setStatusCode(403);
        }

        $capresModel = model('App\Models\CapresModel');

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = @$this->request->getGet('search')['value'];
        $result = $capresModel->fetch($draw, $start, $length, $search);

        foreach ($result['data'] as &$data) {
            $obj = $data;

            $actEdit = '<a class="btn" href="'.site_url('admin/capres/edit').'?id='.$obj->id.'"><i class="fa fa-edit left"></i> EDIT</a>';
            $actDelete = '<a class="btn red" href="'.site_url('admin/capres/delete').'?id='.$obj->id.'" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash left"></i> DELETE</a>';

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
        $capresModel = model('App\Models\CapresModel');

        $oldID = '';

        $capres = new CapresEntity();
        $capres->ID = '';
        $capres->Nama = '';
        $capres->Visi = '';
        $capres->Misi = '';
        $capres->IDFoto = '';
        if (!$createMode) {
            if ($this->request->getGet('id') === NULL) {
                return FALSE;
            }

            $capres = $capresModel->find($this->request->getGet('id'));
            if (!isset($capres)) {
                $this->session->set('status', new Status('error', 'ID capres tidak valid'));
                return FALSE;
            }
            $oldID = $capres->ID;
        }

        if ($this->request->getPost('submit') == 1) {
            $rules = [];

            $rules['ID'] = 'required';
            $rules['Nama'] = 'required|max_length[200]';
            $rules['Visi'] = 'required';
            $rules['Misi'] = 'required';

            $oldID = $capres->ID;
            $capres->ID = $this->request->getPost('ID');
            $capres->Nama = $this->request->getPost('Nama');
            $capres->Visi = $this->request->getPost('Visi');
            $capres->Misi = $this->request->getPost('Misi');
            $capres->IDFoto = $this->request->getPost('IDFoto');
            $newID = $capres->ID;

            if ($this->validate($rules)) {
                if ($createMode) {
                    if ($capresModel->find($capres->ID) === NULL) {
                        $capresModel->insert($capres);
                        $this->session->set('status', new Status('success', 'Berhasil menambahkan data capres'));
                        return TRUE;
                    } else {
                        $this->session->set('status', new Status('error', 'Capres sudah ada sebelumnya'));
                    }
                } else {
                    if ($capresModel->find($oldID) !== NULL) {
                        $update = FALSE;
                        if ($newID == $oldID) {
                            $update = TRUE;
                        } else {
                            if ($capresModel->find($capres->ID) === NULL) {
                                $update = TRUE;
                            } else {
                                $this->session->set('status', new Status('error', 'ID capres yang baru sudah ada sebelumnya'));
                            }
                        }
                        if ($update) {
                            $capresModel->update($oldID, $capres);
                            $this->session->set('status', new Status('success', 'Berhasil memperbarui capres'));
                            return TRUE;
                        }
                    } else {
                        $this->session->set('status', new Status('error', 'Capres tidak ditemukan'));
                    }
                }
            } else {
                $this->session->set('status', new Status('error', $this->validator->listErrors()));
            }
        }
        $this->initStatus();

        $action = $createMode ? 'Add' : 'Edit';
        echo $this->viewHeader($action.' Capres', TRUE);
        echo view('admin/capres/editor', [
            'capres' => $capres,
            'createMode' => $createMode,
            'action' => site_url(uri_string()).(!$createMode ? '?id='.$oldID : ''),
            'oldID' => $oldID
        ]);
        echo $this->viewFooter();
        return NULL;
    }

}

