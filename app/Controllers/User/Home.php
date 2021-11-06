<?php

namespace App\Controllers\User;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\UserController;

class Home extends UserController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        echo view('header', [
            'title' => 'Home',
            'menus' => $this->menus,
            'status' => $this->status
        ]);
        echo view('user/home/index', [
            'login' => $this->login
        ]);
        echo view('footer');
    }

}

