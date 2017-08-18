<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Injection de dépendences
$container = $app->getContainer();
$container['appConfig'] = ['appName' => 'Slim API', 'maintenance' => true];
$container['database'] = [
    'host' => 'localhost',
    'dbname' => 'bibliotheque',
    'user' => 'root',
    'password' => ''
];
// Récupération de la configuration
$container['pdo'] = function (ContainerInterface $container) {
    $host = $container->get('database')['host'];
    $dbname = $container->get('database')['dbname'];
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
//    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

    return new \PDO($dsn,
        $container->get('database')['user'],
        $container->get('database')['password']
//        $options
    );
};
