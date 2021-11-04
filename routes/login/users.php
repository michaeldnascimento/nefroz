<?php

use \App\Http\Response;
use \App\Controller\Login;

//ROTA DE LISTAGEM DE USUÁRIOS
$obRouter->get('/admin/users', [
    'middlewares' => [
        'required-login',
    ],
    function($request){
        return new Response(200, Admin\User::getUsers($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIO
$obRouter->get('/admin/users/new', [
    'middlewares' => [
        'required-login',
    ],
    function($request){
        return new Response(200, Login\User::getNewUser($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIO (POST)
$obRouter->post('/login/users/new', [
    'middlewares' => [
        //'required-login',
    ],
    function($request){
        return new Response(200, Login\User::setNewUser($request));
    }
]);

//ROTA DE RECUPERAÇÃO DE SENHA
$obRouter->post('/login/users/recover', [
    'middlewares' => [
        //'required-login',
    ],
    function($request){
        return new Response(200, Login\User::recoverPassword($request));
    }
]);

//ROTA DE RECUPERAÇÃO DE SENHA
$obRouter->get('/login/users/recover/{token}/{email}', [
    'middlewares' => [
        //'required-login',
    ],
    function($request, $token, $email){
        return new Response(200, Login\User::recoverTokenValidation($request, $token, $email));
    }
]);

//ROTA DE CADASTRO DE UMA NOVA SENHA (POST)
$obRouter->post('/login/users/newPass', [
    'middlewares' => [
        //'required-login',
    ],
    function($request){
        return new Response(200, Login\User::setNewPassword($request));
    }
]);

//ROTA DE EDIÇÃO DE UM NOVO USUÁRIO
$obRouter->get('/admin/users/{id}/edit', [
    'middlewares' => [
        'required-login',
    ],
    function($request, $id){
        return new Response(200, Admin\User::getEditUser($request, $id));
    }
]);

//ROTA DE EDIÇÃO DE UM NOVO USUÁRIO (POST)
$obRouter->post('/admin/users/{id}/edit', [
    'middlewares' => [
        'required-login',
    ],
    function($request, $id){
        return new Response(200, Admin\User::setEditUser($request, $id));
    }
]);


//ROTA DE EXCLUSÃO DE UM NOVO USUÁRIO
$obRouter->get('/admin/users/{id}/delete', [
    'middlewares' => [
        'required-login',
    ],
    function($request, $id){
        return new Response(200, Admin\User::getDeleteUser($request, $id));
    }
]);

//ROTA DE EXCLUSÃO DE UM NOVO USUÁRIO (POST)
$obRouter->post('/admin/users/{id}/delete', [
    'middlewares' => [
        'required-login',
    ],
    function($request, $id){
        return new Response(200, Admin\User::setDeleteUser($request, $id));
    }
]);