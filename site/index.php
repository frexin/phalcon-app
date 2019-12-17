<?php

use app\components\SimpleHttpClient;
use Phalcon\Loader;
use Phalcon\Mvc\View\Simple;

$loader = new Loader();
$loader->registerNamespaces(['app\components' => 'components']);
$loader->register();

$app = new Phalcon\Mvc\Micro();
$app['view'] = function () {
    $view = new Simple();
    $view->setViewsDir('views/');

    return $view;
};

$app->get('/', function () use ($app) {
    echo $app['view']->render('main');
});

$app->post('/', function () use ($app) {
    $httpClient = new SimpleHttpClient("http://phalcon-app/auth");
    $httpClient->setData([
        'login' => $app->request->getPost('login'),
        'password' => $app->request->getPost('password')
    ]);

    $response_data = [];
    $httpClient->executeRequest();

    if (!$error = $httpClient->getLastError()) {
        $response_data = $httpClient->getResponse();
    }

    echo $app['view']->render('main', ['response' => (array) $response_data, 'error' => $error]);
});

$app->handle();
