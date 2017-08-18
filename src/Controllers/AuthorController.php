<?php

namespace app\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use RedBeanPHP\R as R;

class AuthorController
{
    public function index(Request $request, Response $response) {
        $data = R::findAll('auteurs');
        $data = R::exportAll($data);

        return $response->withJson($data);
    }
}