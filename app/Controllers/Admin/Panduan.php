<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;

class Panduan extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        if (!$this->adminLogin) {
            return redirect()->to('admin/auth/login');
        }

        echo $this->viewHeader('Panduan Operasional', TRUE);
        echo view('admin/panduan/index');
        echo $this->viewFooter();
    }

}

