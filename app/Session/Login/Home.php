<?php

namespace App\Session\Login;

use \App\Model\Entity\User;

class Home {

    /**
     * Método responsável por iniciar a sessão
     */
    private static function init()
    {
        //VERIFICA SE A SESSÃO NÃO ESTÁ ATIVA
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * Método responsável por criar o login do usuário
     * @param User $obUser
     * @return boolean
     */
    public static function login($obUser)
    {

        //INICIA A SESSÃO
        self::init();

        //DEFINE A SESSÃO DO USUÁRIO
        $_SESSION['nefroz']['login']['usuario'] = [
            'id'    => $obUser['id'],
            'nome'  => $obUser['nome'],
            'email' => $obUser['email']
        ];

        //SUCESSO
        return true;

    }


    /**
     * Método responsável por verificar se o usuário está logado
     * @return boolean
     */
    public static function isLogged()
    {
        //INICIA A SESSÃO
        self::init();

        //var_dump($_SESSION);

        //RETORNA A VERIFICAÇÃO
        return isset($_SESSION['nefroz']['login']['usuario']['id']);

    }

    /**
     * Método responsável por executar o logout do usuário
     * @return boolean
     */
    public static function logout()
    {
        //INICIA A SESSÃO
        self::init();

        //DESLOGA O URUÁRIO
        unset($_SESSION['nefroz']['login']['usuario']);

        //SUCESSO
        return true;
    }

    /**
     * Método responsável por retornar a session do usuário
     * @return string
     */
    public static function getSession()
    {

        //RETORNA A SESSION
        return $_SESSION['nefroz']['login']['usuario'];

    }
}