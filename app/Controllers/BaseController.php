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

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();

        $this->initStatus();
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
}

