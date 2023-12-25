<?php

include_once 'modules/account/dao/UserDao.php';
include_once 'modules/account/controllers/userController.php';

$userDao = new UserDao();
$userController = new UserController($userDao);

$route->get('/user/listAll', [$userController, 'index']);
$route->get('/user/list/:id', [$userController, 'show']);
$route->post('/user/add', [$userController, 'store']);
$route->put('/user/update/:id', [$userController, 'update']);
$route->delete('/user/delete/:id', [$userController, 'destroy']);
