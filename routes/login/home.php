<?php

use \App\Http\Response;
use \App\Controller\Login;

//ROTA LOGIN
$obRouter->get('/login', [
    'middlewares' => [
        'required-logout',
    ],
    function($request){
        return new Response(200, Login\Home::getLogin($request));
    }
]);

//ROTA LOGIN (POST)
$obRouter->post('/login', [
    'middlewares' => [
        'required-logout',
    ],
    function($request){
        return new Response(200, Login\Home::setLogin($request));
    }
]);


//ROTA LOGOUT
$obRouter->get('/login/logout', [
    'middlewares' => [
        'required-login',
    ],
    function($request){
        return new Response(200, Login\Home::setLogout($request));
    }
]);