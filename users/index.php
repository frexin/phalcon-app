<?php

use Phalcon\Db\Adapter\Pdo\Sqlite;
use Phalcon\Http\Request;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;

defined('APP_PATH') || define('APP_PATH', __DIR__ . '/');

$loader = new Loader();
$loader->registerDirs([
    __DIR__ . '/models/'
]);
$loader->register();

$di = new FactoryDefault();
$di->set('db', function () {
    return new Sqlite([
        'adapter'     => 'Sqlite',
        'dbname'      => APP_PATH . '/db.sqlite',
        'schema'      => 'main'
    ]);
});
$app = new Micro($di);

$app->post('/auth', function () use ($app) {
    $userModel = new Users();

    $userModel->login = $app->request->getPost('login');
    $userModel->password = $app->request->getPost('password');

    if (!$userModel->validation() && $errors = $userModel->getMessages()) {
        $data = ['code' => '422', 'status' => 'error', 'message' => 'Data validation failed'];

        foreach ($errors as $error) {
            $data['payload'][] = $error->getMessage();
        }

        $app->response->setStatusCode(422);
    }
    else {
        $user = Users::findFirst(['login = :login:', 'bind' => ['login' => $userModel->login]]);

        if ($user && $app->security->checkHash($userModel->password, $user->password)) {
            $data = ['code' => '200', 'status' => 'success', 'message' => 'Successfull login'];
        }
        else {
            $app->response->setStatusCode(403);
            $data = ['code' => '403', 'status' => 'error', 'message' => 'Incorrect creds'];
        }
    }

    $app->response->setJsonContent($data);

    return $app->response;
}
);

$app->handle();
