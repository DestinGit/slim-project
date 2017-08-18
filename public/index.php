<?php

use Psr\Container\ContainerInterface;

require __DIR__ . '/../vendor/autoload.php';

//use Slim\Http\Request;
//use Slim\Http\Response;

// Instanciation de l'application
$app = new \Slim\App();

// Injection de dépendances
require __DIR__ . '/../src/config/dependencies.php';

// Middlewares
require __DIR__ . '/../src/config/middlewares.php';
// Définition des routes
require __DIR__ . '/../src/config/routes.php';

// Exécution de la'application
$app->run();