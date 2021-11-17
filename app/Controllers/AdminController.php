<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AdminController extends BaseController {

    protected $adminLogin = FALSE;

    protected $loginRedirect;
    protected $dashboardRedirect;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        $this->loginRedirect = redirect()->to('admin/auth/login');
        $this->dashboardRedirect = redirect()->to('admin/dashboard');

        if (!empty($this->session->get('admin_login'))) {
            $this->adminLogin = TRUE;
        }

        if ($this->adminLogin) {
            $this->menus[] = ['name' => 'Dashboard', 'site' => 'admin/dashboard'];
            $this->menus[] = ['name' => 'Data Prodi', 'site' => 'admin/prodi/view'];
            $this->menus[] = ['name' => 'Data Sesi', 'site' => 'admin/sesi/view'];
            $this->menus[] = ['name' => 'Data Capres', 'site' => 'admin/capres/view'];
            $this->menus[] = ['name' => 'Data Caleg', 'site' => 'admin/caleg/view'];
            $this->menus[] = ['name' => 'Data Mahasiswa', 'site' => 'admin/mahasiswa/view'];
            $this->menus[] = ['name' => 'Resources', 'site' => 'admin/resource/view'];
            $this->menus[] = ['name' => 'Data Pemilih', 'site' => 'admin/pemilih/view'];
            $this->menus[] = ['name' => 'Rekapitulasi', 'site' => 'admin/rekapitulasi'];
            $this->menus[] = ['name' => 'Layanan Sengketa', 'site' => 'admin/sengketa'];
            $this->menus[] = ['name' => 'Keluar', 'site' => 'admin/auth/logout'];
        } else {
            $this->menus[] = ['name' => 'Masuk', 'site' => 'admin/auth/login'];
        }
    }

}

