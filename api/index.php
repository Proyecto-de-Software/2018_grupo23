<?php

require 'vendor/autoload.php';
require 'APIController.php';

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

    // Add route callbacks
    $app->get('/', function ($request, $response, $args) {
        return $response->withStatus(200)->write('Bienvenido a nuestra API de instituciones!');
    });
    $app->get('/instituciones', function ($request, $response, $args) {
        echo APIController::getInstance()->list_institutions();
    });

    $app->get('/instituciones/{id}', function ($request, $response, $args) {
        echo APIController::getInstance()->list_institution($args['id']);
    });

    $app->get('/instituciones/region-sanitaria/{id}', function ($request, $response, $args) {
        echo APIController::getInstance()->list_institution_by_RS($args['id']);
    });

    // Run application
    $app->run();
