<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Libraries\Status;

class BaseController extends Controller {

    protected $session;

    protected $status;

    protected $menus = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();

        $this->initStatus();

        date_default_timezone_set('Asia/Jakarta');
    }

    protected function initStatus() {
        if ($this->status === NULL) {
            $this->status = new Status();
        }
        if ($this->session->get('status') !== NULL) {
            $this->status = $this->session->get('status');
            $this->session->remove('status');
        }
    }

    protected function viewHeader($title, $sidebarOnly = FALSE) {
        return view('header', [
            'title' => $title,
            'menus' => $this->menus,
            'status' => $this->status,
            'sidebarOnly' => $sidebarOnly
        ]);
    }

    protected function viewFooter() {
        return view('footer');
    }
}

