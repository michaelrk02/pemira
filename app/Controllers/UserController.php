<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class UserController extends BaseController {

    protected $login;
    protected $login_redirect;

    protected $home_redirect;

    protected $menus = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        $this->login = $this->session->get('user_login');
        if ($this->login === NULL) {
            $this->login_redirect = redirect()->to(site_url('user/auth/login'));
        }

        $this->home_redirect = redirect()->to(site_url('user/auth/home'));

        $this->menus = [
            ['name' => 'Beranda', 'site' => 'user/home'],
            ['name' => 'Voting', 'site' => 'user/vote']
        ];

        if ($this->login !== NULL) {
            $this->menus[] = ['name' => 'Profile', 'site' => 'user/profile'];
        }
    }

}

