<?php

use Psr\Container\ContainerInterface;

require __DIR__ . '/../vendor/autoload.php';
use Slim\Http\Request;
use Slim\Http\Response;
// Instanciation de l'application
$app = new \Slim\App();

// Injection de dépendences
$container = $app->getContainer();
$container['appConfig'] = ['appName'=> 'Slim API', 'maintenance'=>true];
$container['database'] = [
    'host'=>'localhost',
    'dbname'=>'bibliotheque',
    'user'=>'root',
    'password'=>''
];
$container['pdo'] = function (ContainerInterface $container) {
    $host = $container->get('database')['host'];
    $dbname = $container->get('database')['dbname'];
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";

    return new \PDO($dsn,
        $container->get('database')['user'],
        $container->get('database')['password']
        );
};

// Middlewares
//$app->add(function (Request $request, Response $response, Callable $next){
//    $response->getBody()->write("Nous sommes le ". date("d/m/Y"));
//
//    return $next($request, $response);
//});
//
//$app->add(function (Request $request, Response $response, Callable $next){
//    $response = $next($request, $response);
//
//    $response->getBody()->write("Good bye");
//    return $response;
//});
//
//$app->add(function (Request $request, Response $response, Callable $next){
//    $maintenance = $this->get("appConfig")["maintenance"]??false;
//    if($maintenance){
//        $message = "Le site est en maintenance, revenez plus tard";
//        $response->getBody()->write($message);
//    } else {
//        $next($request, $response);
//    }
//    return $response;
//});

// OU

$dateMiddleware = function (Request $request, Response $response, Callable $next){
    $response->getBody()->write("Nous sommes le ". date("d/m/Y"));

    return $next($request, $response);
};

$goodByeMiddleware = function (Request $request, Response $response, Callable $next){
    $response = $next($request, $response);

    $response->getBody()->write("Good bye");
    return $response;
};

$maintenanceMiddleware = function (Request $request, Response $response, Callable $next){
    $maintenance = $this->get("appConfig")["maintenance"]??false;
    if($maintenance){
        $message = "Le site est en maintenance, revenez plus tard";
        $response->getBody()->write($message);
    } else {
        $next($request, $response);
    }
    return $response;
};

$apiProtection = function (Request $request, Response $response, Callable $next) {
  $apiKey = 123;
  $requestApi = $request->getParam('API_KEY') ?? null;
  if ($apiKey == $requestApi) {
      $newResponse = $next($request, $response);
  } else {
      $message = 'Accès non autorisé';
      $newResponse = $response->withStatus(403);
      $newResponse->getBody()->write($message);
  }

  return $newResponse;
};
// Définition des routes
require __DIR__ . '/../src/routes.php';

// Exécution de la'application
$app->run();