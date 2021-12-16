<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\AdminController;
use App\Libraries\Status;

class Auth extends AdminController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function login() {
        if ($this->adminLogin) {
            return $this->dashboardRedirect;
        }

        if ($this->request->getPost('submit') == 1) {
            $password = $this->request->getPost('password');

            if ($password === $_ENV['pemira.admin.password']) {
                $this->session->set('admin_login', TRUE);
                $this->session->set('status', new Status('success', 'Login berhasil! Selamat datang di admin dashboard. Anda juga dapat melihat panduan di menu kiri'));
                return $this->dashboardRedirect;
            } else {
                $this->session->set('status', new Status('error', 'Password tidak valid'));
            }
        }
        $this->initStatus();

        echo $this->viewHeader('Admin Login', TRUE);
        echo view('admin/auth/login');
        echo $this->viewFooter();
    }

    public function logout() {
        $this->session->remove('admin_login');
        return $this->loginRedirect;
    }

}

