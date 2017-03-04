<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$config["displayErrorDetails"] = true;
$config["addContentLengthHeader"] = false;
$config["db"]["host"] = "localhost";
$config["db"]["user"] = "root";
$config["db"]["password"] = "";
$config["db"]["dbname"] = "academia-digital";

$app = new \Slim\App(["settings" => $config]);
$app->get('/customers', function (Request $request, Response $response) {

	$data = array(
		array("firstName" => "Stanley", "lastName" => "Sathler"),
		array("firstName" => "Isabella", "lastName" => "Garbazza")
	);

    $newResponse = $response->withJson($data);
    return $newResponse;
});
$app->run();
