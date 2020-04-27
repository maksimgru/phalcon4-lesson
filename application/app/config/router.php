<?php

$router = $di->getRouter();

$router->add('/', ['controller' => 'index', 'action' => 'index'])->setName('home');

$router->add('/user/index', ['controller' => 'user', 'action' => 'index'])->setName('users');
$router->add('/user/login', ['controller' => 'user', 'action' => 'login'])->setName('login');
$router->add('/user/login/submit', ['controller' => 'user', 'action' => 'loginSubmit'])->setName('login-submit');
$router->add('/user/register', ['controller' => 'user', 'action' => 'register'])->setName('register');
$router->add('/user/register/submit', ['controller' => 'user', 'action' => 'registerSubmit'])->setName('register-submit');
$router->add('/user/profile', ['controller' => 'user', 'action' => 'profile'])->setName('profile');
$router->add('/user/logout', ['controller' => 'user', 'action' => 'logout'])->setName('logout');


$router->handle($_SERVER['REQUEST_URI']);
