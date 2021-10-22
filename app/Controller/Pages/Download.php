<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Download extends Page {

    /**
     * Método responsavel por retornar o conteudo (view) da nossa pagina de sobre
     * @return string
     */
    public static function getDownload()
    {

        //VIEW DA HOME
        $content =  View::render('pages/about', [
            'name'        => $obOrganization->name,
            'description' => $obOrganization->description,
            'site'        => $obOrganization->site
        ]);

        //RETORNA A VIEW DA PAGINA
        return parent::getPage('SOBRE - WDEV', $content);
    }

}