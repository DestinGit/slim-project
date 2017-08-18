<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Définition des routes
$app->get('/hello', function (Request $request, Response $response) {
    $name = $request->getParam('name') ?? 'world';
    return $response->getBody()->write("Hello $name");
})->add($dateMiddleware);

$app->get("/hello/{name}[/{age:\d{1,3}}]", function (Request $request, Response $response, array $args) {
    $html = "<h1>Hello ". $args['name']. "</h1>";
    if (isset($args['age'])) {
        $html .= "<h2> Vous avez {$args["age"]} ans </h2>";
    }
    return $response->getBody()->write($html);
})
    ->setName('hello') // On nomme notre route pour l'utiliser dans un lien par exemple
    ->add($goodByeMiddleware);

$app->get('/list', function (Request $request, Response $response) {
    $url = $this->get('router')->pathFor('hello', ['name'=>'Alfred', 'age'=>58]);

    $link = "<a href=$url>Lien vers Alfred</a>";

    return $response->getBody()->write($link);
})->add($maintenanceMiddleware);

$app->get('/api/user/list', function (Request $request, Response $response) {
    $users = [
        ['username' => 'mobali', 'email'=>'yemei@mopao.co', 'id' => 1],
        ['username' => 'tino', 'email'=>'quadra@mopao.co', 'id' => 2]
    ];

    return $response->withJson($users);
});

//$app->get('/livres', function (Request $request, Response $response) {
//    $sql = "SELECT * FROM livres";
//    $pdo = $this->get('pdo');
//
//    /**
//     * @var \PDO
//     */
//    $data = $pdo->query($sql)
//                ->fetchAll(\PDO::FETCH_ASSOC);
//
//    return $response->withJson($data);
//});


$app->group('/api', function () use ($app) {

    $app->get('/livres', function (Request $request, Response $response) {
        $sql = "SELECT * FROM livres";
        $pdo = $this->get('pdo');

        /**
         * @var \PDO
         */
        $data = $pdo->query($sql)
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $response->withJson($data);
    });

    $app->get('/livre/{id:\d+}', function (Request $request, Response $response, array $args) {
        $sql = "SELECT * FROM livres WHERE id = :id";
        $pdo = $this->get('pdo');

        /** @var \PDO */
         $statement = $pdo->prepare($sql);
         $statement->execute($args);

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $response->withJson($data);
    });


})->add($apiProtection);