<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Libraries\ResourceManager;
use App\Libraries\Status;
use App\Libraries\WebToken;

class Resource extends AdminController {

    protected $manager;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        $this->manager = new ResourceManager();
    }

    public function view() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        if ($this->request->getGet('id') !== NULL) {
            return redirect()->to(site_url('resource').'?token='.urlencode(WebToken::fromData(['expired' => time() + 5], ['id' => $this->request->getGet('id')])->toString()));
        }

        echo $this->viewHeader('Resources', TRUE);
        echo view('admin/resource/view', [
            'resources' => $this->manager->getResourceList()
        ]);
        echo $this->viewFooter();
    }

    public function create() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $input = [];
        $input['id'] = '';
        $input['mime'] = '';
        $input['autodetectMIME'] = TRUE;
        $input['cacheDuration'] = '';
        $input['disableCaching'] = TRUE;

        if ($this->request->getPost('submit') == 1) {
            $id = $this->request->getPost('id');
            $autodetectMIME = !empty($this->request->getPost('autodetectMIME'));
            $disableCaching = !empty($this->request->getPost('disableCaching'));

            $rules = [];
            $rules['id'] = 'required|max_length[255]|regex_match[/[a-z\._]*/]';
            $rules['cacheDuration'] = 'permit_empty|is_natural';
            $rules['file'] = 'uploaded[file]';

            if ($this->validate($rules)) {
                $file = $this->request->getFile('file');
                if ($file->isValid()) {
                    $succeeded = FALSE;
                    $stream = fopen($file->getTempName(), 'r');
                    if ($stream !== FALSE) {
                        $succeeded = $this->manager->putStream($id, $stream);
                        fclose($stream);
                    }

                    $mime = NULL;
                    if (!$autodetectMIME) {
                        $mime = $this->request->getPost('mime');
                    } else {
                        $mime = mime_content_type($file->getTempName());
                    }

                    $metadata = [];
                    $metadata['mime'] = $mime;
                    if (!$disableCaching) {
                        $metadata['cache'] = $this->request->getPost('cacheDuration');
                    }
                    $succeeded = $succeeded ? $this->manager->putMetadata($id, $metadata) : FALSE;

                    if ($succeeded) {
                        $this->session->set('status', new Status('success', 'Resource was created successfully'));
                        return redirect()->to('admin/resource/view');
                    } else {
                        $this->session->set('status', new Status('error', 'Failed to read uploaded file'));
                    }
                } else {
                    $this->session->set('status', new Status('error', 'Failed to upload file'));
                }
            } else {
                $this->session->set('status', new Status('error', $this->validator->listErrors()));
            }

            $input['id'] = $id;
            $input['mime'] = $this->request->getPost('mime');
            $input['autodetectMIME'] = $autodetectMIME;
            $input['cacheDuration'] = $this->request->getPost('cacheDuration');
            $input['disableCaching'] = $disableCaching;
        }
        $this->initStatus();

        echo $this->viewHeader('Create Resource', TRUE);
        echo view('admin/resource/create', [
            'input' => $input
        ]);
        echo $this->viewFooter();
    }

    public function drop() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        $id = $this->request->getGet('id');
        if (isset($id)) {
            if ($this->manager->delete($id)) {
                $this->session->set('status', new Status('success', 'Successfully deleted the requested resource'));
            } else {
                $this->session->set('status', new Status('error', 'Failed to delete requested resource due to permission rights or nonexistent file'));
            }
        }

        return redirect()->to('admin/resource/view');
    }

}

