<?php
use Slim\Http\Request;
use Slim\Http\Response;

// MIDDLEWARE
/**
 * Un middleware capture la requête, effectue un traitement
 * généralement sur la réponse et exécute le prochain middleware
 * Ici le middleware s'applique à toutes les routes de l'application
 */

//
// MIDDLEWARE POUR TOUTES LES ROUTES
//

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

/*
Attention, le dernier middleware déclaré est le premier
exécuté
*/
/**
 * Ce middleware ne renvoie pas la requête aux autres middlewares
 * Il interrompt donc la chaîne des middlewares
 */

//
// MIDDLEWARE POUR UNE ROUTE
//
$dateMiddleware = function (Request $request, Response $response, Callable $next) {
    $response->getBody()->write("Nous sommes le " . date("d/m/Y"));

    return $next($request, $response);
};

$goodByeMiddleware = function (Request $request, Response $response, Callable $next) {
    $response = $next($request, $response);

    $response->getBody()->write("Good bye");
    return $response;
};

$maintenanceMiddleware = function (Request $request, Response $response, Callable $next) {
    $maintenance = $this->get("appConfig")["maintenance"] ?? false;
    if ($maintenance) {
        $message = "Le site est en maintenance, revenez plus tard";
        $response->getBody()->write($message);
    } else {
        $next($request, $response);
    }
    return $response;
};
// Gestion d'une clef d'API
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

// récupérer des attributs d'un middleware
/*
$middleware = function (Request $request, Response $response, Callable $next){
    $response = $next($request, $response, $next);
    $id = $request->getAttribute("tab");
    $response->getBody()->write("Good Bye $tab");
    return $response;
};
*/
// Passer des attributs à un middleware
/*
$setAttributeMw = function (Request $request, Response $response, Callable $next) {
    $request = $request->withAttribute('tab', serialize([1,2,3,4]));
    $response = $next($request, $response);

    return $response;
};
*/
