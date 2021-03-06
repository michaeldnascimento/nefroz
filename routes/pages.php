<?php

use \App\Http\Response;
use \App\Controller\Pages;

//ROTA HOME
$obRouter->get('/', [
    'middlewares' => [
        //'cache'
        'required-login',
    ],
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);


//ROTA DOWNLOAD
$obRouter->get('/download', [
    'middlewares' => [
        //'cache'
        'required-login'
    ],
    function($request){
        return new Response(200, Pages\Download::getDownload($request));
    }
]);

//ROTA DOWNLOAD EXECUTAR
$obRouter->get('/download/{version}', [
    'middlewares' => [
        //'cache'
        'required-login'
    ],
    function($request, $version){
        return new Response(200, Pages\Download::startDownload($request, $version));
    }
]);


//ROTA DINAMICA
/**
$obRouter->get('/pagina/{idPagina}/{acao}', [
    function($idPagina, $acao){
        return new Response(200, 'Pag '.$idPagina. ' - '.$acao);
    }
]);
 */