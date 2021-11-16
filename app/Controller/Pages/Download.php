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
        $request->getRouter()->redirect('/resources/view/pages/download/fisiopatologia_renal_v1.0.1.rar');

    }

    /**
     * Método responsavel por retornar o conteudo (view) da nossa pagina de sobre
     * @param Request $request
     * @return string
     */
    public static function getDownload($request)
    {

        //VERIFICA SE O USUÁRIO ESTÁ LOGADO NO INTRANET
        if (!isset($_SESSION["usuario"])) {

            //RETORNA SESSION USUARIO
            $user = SessionLogin::getSession();

            //VALIDA E-MAIL DO USUÁRIO
            $obUser = EntityUser::getUserByEmail($user['email']);

            if ($obUser == '') {
                //REDIRECIONA O USUÁRIO
                $request->getRouter()->redirect('/?status=location');
            }

        }else{

            //ARMAZENA AS VARIAVEIS DE SESSÃO
            $user['nome'] = $_SESSION['usuario']->nome;
            $user['email'] = $_SESSION['usuario']->email;

        }

        //NOVA INSTANCIA DE DOWNLOAD
        $obUser = new EntityDownload();
        $obUser->name = $user['nome'];
        $obUser->email = $user['email'];
        $obUser->version = "1.0.1";
        $obUser->cadastrar();

        //START DOWNLOAD
        self::startDownload($request);

    }

}