<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

$loader = require  '../vendor/autoload.php';

$loader->addPsr4('App\\', __DIR__.'../app/');
$config = require  '../config/app.php';

$app = new \Slim\App($config);

$container = $app->getContainer();

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
    	if($c->get('settings')['displayErrorDetails'] == true){
    		$msg = $exception->getMessage();
    	} else {
    		$msg = 'Internal Server Error';
    	}

    	$r = [
    		'status' => false,
    		'msgDec' => $msg
    	];

        return $c['response']->withStatus(500)
                             ->withHeader('Content-Type', 'text/html')
                             ->withJson($r);
    };
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $r = [
            'status' => false,
            'msgDec' => 'Page Not Found'
        ];

        return $c['response']->withStatus(404)
                             ->withHeader('Content-Type', 'text/html')
                             ->withJson($r);
    };
};

require __DIR__ . '/../app/routes.php';
require __DIR__ . '/../app/helper.php';

$app->run();