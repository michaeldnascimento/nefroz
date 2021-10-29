<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Session\Login\Home as SessionLogin;
use \App\Model\Entity\User as EntityUser;
use \App\Model\Entity\Download as EntityDownload;

class Download extends Page {

    /**
     * Método responsável por iniciar o download
     * @param Request $request
     */
    public static function startDownload($request){

        //INICIAR O DOWNLOAD
        $request->getRouter()->redirect('/resources/view/pages/download/fisiopatologia_renal_v1.0.zip');

    }

    /**
     * Método responsavel por retornar o conteudo (view) da nossa pagina de sobre
     * @param Request $request
     * @return string
     */
    public static function getDownload($request)
    {

        //RETORNA SESSION USUARIO
        $user = SessionLogin::getSession();

        //VALIDA E-MAIL DO USUÁRIO
        $obUser = EntityUser::getUserByEmail($user['email']);

        if ($obUser == '') {
            //REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/?status=location');
        }

        //NOVA INSTANCIA DE DOWNLOAD
        $obUser = new EntityDownload();
        $obUser->name = $user['nome'];
        $obUser->email = $user['email'];
        $obUser->version = 1;
        $obUser->cadastrar();

        //START DOWNLOAD
        self::startDownload($request);

    }

}