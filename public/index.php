<?php
require __DIR__ . '/../vendor/autoload.php';
use Slim\Http\Request;
use Slim\Http\Response;

// Instanciation de l'application
$app = new \Slim\App();

// Définition des routes
$app->get('/hello', function (Request $request, Response $response) {
    $name = $request->getParam('name') ?? 'world';
    return $response->getBody()->write("Hello $name");
});

$app->get("/hello/{name}[/{age:\d{1,3}}]", function (Request $request, Response $response, array $args) {
    $html = "<h1>Hello ". $args['name']. "</h1>";
    if (isset($args['age'])) {
        $html .= "<h2> Vous avez {$args["age"]} ans </h2>";
    }
    return $response->getBody()->write($html);
})
    ->setName('hello'); // On nomme notre route pour l'utiliser dans un lien par exemple

$app->get('/list', function (Request $request, Response $response) {
    $url = $this->get('router')->pathFor('hello', ['name'=>'Alfred', 'age'=>58]);

    $link = "<a href=$url>Lien vers Alfred</a>";

    return $response->getBody()->write($link);
});
// Exécution de la'application
$app->run();