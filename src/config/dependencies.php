<?php

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

// Injection de dépendences
$container = $app->getContainer();
$container['appConfig'] = ['appName' => 'Slim API', 'maintenance' => true];
$container['database'] = [
    'user' => 'root',
    'password' => '',
    'dsn' => 'mysql:host=localhost;dbname=bibliotheque;charset=utf8'
];


// Récupération de la configuration
$container['pdo'] = function (ContainerInterface $container) {
    $dsn = $container->get('database')['dsn'];
    return new \PDO(
        $dsn,
        $container->get('database')['user'],
        $container->get('database')['password']
    );
};
