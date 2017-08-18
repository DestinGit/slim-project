<?php
use Slim\Http\Request;
use Slim\Http\Response;

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

$app->get('/api/user/list', function (Request $request, Response $response) {
    $users = [
        ['username' => 'mobali', 'email'=>'yemei@mopao.co', 'id' => 1],
        ['username' => 'tino', 'email'=>'quadra@mopao.co', 'id' => 2]
    ];

    return $response->withJson($users);
});

$app->get('/livres', function (Request $request, Response $response) {
    $sql = "SELECT * FROM livres";
    $pdo = $this->get('pdo');

    /**
     * @var \PDO
     */
    $data = $pdo->query($sql)
                ->fetchAll(\PDO::FETCH_ASSOC);
//var_dump($data);
    return $response->withJson($data);
});