<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Libraries\ResourceManager;
use App\Libraries\WebToken;

class Resource extends Controller {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index() {
        $token = WebToken::fromString($this->request->getGet('token'));
        if (isset($token)) {
            $id = $token->payload['id'];
            if (isset($id)) {
                $manager = new ResourceManager();
                if ($manager->exists($id)) {
                    $metadata = $manager->getMetadata($id);

                    if (isset($metadata['mime'])) {
                        $this->response->setContentType($metadata['mime']);
                    }
                    if (isset($metadata['cache'])) {
                        $this->response->setCache(['max-age' => $metadata['cache']]);
                    }
                    $this->response->send();

                    $succeeded = FALSE;
                    $output = fopen('php://output', 'w');
                    if ($output !== FALSE) {
                        $this->response->setStatusCode(200);
                        if ($manager->getStream($id, $output)) {
                            $succeeded = TRUE;
                        }
                        fclose($output);
                    }

                    if ($succeeded) {
                        return;
                    }
                } else {
                    return $this->response->setStatusCode(404);
                }
            } else {
                return $this->response->setStatusCode(400);
            }
        } else {
            return $this->response->setStatusCode(403);
        }
        return $this->response->setStatusCode(500);
    }

}

