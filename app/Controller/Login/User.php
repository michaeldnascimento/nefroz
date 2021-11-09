<?php

namespace App\Controller\Login;

use App\Http\Request;
use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \App\Model\Entity\Recover as EntityRecover;
use \App\Model\Entity\Email as EntityEmail;

class User extends Page {


    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return string
     */
    public static function setNewUser($request)
    {

        //POST VARS
        $postVars = $request->getPostVars();
        $email = isset($postVars['email']) ? $postVars['email'] : '';
        $nome  = isset($postVars['nome']) ? $postVars['nome'] : '';
        $senha = isset($postVars['senha']) ? $postVars['senha'] : '';
        $confirmaSenha = isset($postVars['confirmaSenha']) ? $postVars['confirmaSenha'] : '';

        //VERIFICA A VALIDAÇÃO DE SENHA
        if ($senha != $confirmaSenha){
            //REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/login?status=confirmation');
        }

        //VALIDA E-MAIL DO USUÁRIO
        $obUser = EntityUser::getUserByEmail($email);

        //VERIFICA SE O USUÁRIO JÁ EXISTE
        if ($obUser != ''){
            //REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/login?status=duplicated');
        }

        //NOVA INSTANCIA DE USUÁRIO
        $obUser = new EntityUser();
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha =  password_hash($senha, PASSWORD_DEFAULT);
        $obUser->cadastrar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/login?status=created');

    }

    /**
     * Método responsável por recuperar a senha do usuário
     * @param Request $request
     * @return string
     */
    public static function recoverPassword($request)
    {

        //POST VARS
        $postVars = $request->getPostVars();
        $email = isset($postVars['resetEmail']) ? $postVars['resetEmail'] : '';

        //VALIDA E-MAIL DO USUÁRIO
        $obRecover = EntityUser::getUserByEmail($email);

        //VERIFICA SE O LOGIN EXISTE
        if ($obRecover == ''){
            //REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/login?status=notFound');
        }

        //NOVA INSTANCIA DE USUÁRIO
        $obRecover = new EntityRecover();
        $obRecover->login = $email;
        $obRecover->token = sha1(date('Y-m-d H:i:s'));
        $obRecover->cadastrar();

        //VERIFICA O ID RECUPERAÇÃO
        if ($obRecover->id == ''){
            $request->getRouter()->redirect('/login?status=erroRecover');
        }

        //ENVIAR E-MAIL COM O RESET SENHA
        $entityEmail = new EntityEmail();
        $sendEmail = $entityEmail->emailPasswordRecover($obRecover);

        //VERIFICA SE O E-MAIL FOI ENVIADO
        if ($sendEmail == ''){
            $request->getRouter()->redirect('/login?status=errorEmail');
        }

        //RETORNA PARA LOGIN
        $request->getRouter()->redirect('/login?status=send');

    }

    /**
     * Método responsável por validar o token recebido da recuperação de senha
     * @param Request $request
     * @param string $token
     * @param string $email
     * @return string
     */
    public static function recoverTokenValidation($request, $token, $email)
    {
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obToken = EntityRecover::tokenValidation($token);

        //VERIFICA SE O TOKEN NÃO SOFREU ALTERAÇÕES
        if ($token != $obToken['token']){
            $request->getRouter()->redirect('/login?status=changedToken');
        }

        //VALIDA E-MAIL DO USUÁRIO
        $obRecover = EntityUser::getUserByEmail($email);

        //VERIFICA SE O LOGIN EXISTE
        if ($obRecover == ''){
            //REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/login?status=notFound');
        }

        //CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('login/recover', [
            'token'    => $token,
            'email'    => $email,
            //'status'   => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('Nova Senha Usuário > Nefroz', $content);
    }

    /**
     * Método responsável por cadastrar uma nova senha no banco
     * @param Request $request
     * @return string
     */
    public static function setNewPassword($request)
    {

        //POST VARS
        $postVars = $request->getPostVars();
        $token = isset($postVars['token']) ? $postVars['token'] : '';
        $login = isset($postVars['login']) ? $postVars['login'] : '';
        $senha = isset($postVars['senha']) ? $postVars['senha'] : '';
        $confirmaSenha = isset($postVars['confirmaSenha']) ? $postVars['confirmaSenha'] : '';

        //VERIFICA A VALIDAÇÃO DE SENHA
        if ($senha != $confirmaSenha) {
            //REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/login?status=confirmation');
        }

        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obToken = EntityRecover::tokenValidation($token);

        //VERIFICA SE O TOKEN NÃO SOFREU ALTERAÇÕES
        if ($token != $obToken['token']) {
            $request->getRouter()->redirect('/login?status=changedToken');
        }

        //NOVA INSTANCIA DE RECOVER -
        $obRecover = new EntityRecover();
        $obRecover->id = $obToken['id'];
        $obRecover->login = $obToken['login'];
        $obRecover->token = $obToken['token'];
        $obRecover->status = 0;
        $obRecover->atualizar();

        //VERIFICA SE OS VALORES FORAM PASSADOS
        if ($obRecover->date_update == '') {
            $request->getRouter()->redirect('/login?status=erroRecover');
        }

        //VALIDA E-MAIL DO USUÁRIO
        $user = EntityUser::getUserByEmail($login);

        //ATUALIZA A INSTANCIA
        $obUser = new EntityUser();
        $obUser->id = $user['id'];
        $obUser->nome = $user['nome'];
        $obUser->email = $user['email'];
        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/login?status=updateSuccess');

    }

}