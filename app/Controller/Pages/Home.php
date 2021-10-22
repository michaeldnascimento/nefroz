<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Home extends Page {

    /**
     * Método responsavel por retornar o conteudo (view) da nossa home
     * @return string
     */
    public static function getHome()
    {
        //VIEW DA HOME
        $content =  View::render('pages/home');

        //RETORNA A VIEW DA PAGINA
        return parent::getPage('HOME > Nefroz', $content);
    }

}