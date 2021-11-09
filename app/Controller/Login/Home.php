<?php

namespace App\Controller\Login;

use App\Http\Request;
use App\Model\Entity\User;
use \App\Utils\View;
use \App\Session\Login\Home as SessionLogin;

class Home extends Page {

    /**
     * Método responsável por retornar a renderização da página de login
     * @param Request $request
     * @param string $errorMessage
     * @return string
     */
    public static function getLogin($request, $errorMessage = null)
    {
        //STATUS > Se o errorMessage não for nulo, ele vai exibir a msg, se não ele não vai exibir nada
        //$status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        //CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('login/home', [
            'status'   => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('Login > Nefroz', $content);
    }

    /**
     * Método responsável por definir o login usuário
     * @param Request $request
     */
    public static function setLogin($request)
    {

        //POST VARS
        $postVars = $request->getPostVars();
        $email    = isset($postVars['email']) ? $postVars['email'] : '';
        $senha    = isset($postVars['senha']) ? $postVars['senha'] : '';

        //BUSCA USUÁRIO PELO E-MAIL
        $obUser = User::getUserByEmail($email);

        if ($obUser == ''){
            //return self::getLogin($request, 'E-mail ou senha inválido' );
            $request->getRouter()->redirect('/login?status=invalid');
        }

        //VERIFICA A SENHA DO USUÁRIO > verifica a senha passada, e a senha do banco
        if (!password_verify($senha, $obUser['senha'])){
            //return self::getLogin($request, 'E-mail ou senha inválido' );
            $request->getRouter()->redirect('/login?status=invalid');
        }

        //CRIA A SESSÃO DE LOGIN
        SessionLogin::login($obUser);



//        echo "<pre>";
//        print_r($postVars);
//        echo "<pre>";
//        print_r($obUser);
//        echo "<pre>";
//        print_r($_SESSION);
//        exit;

        //REDIRECIONA O USUÁRIO PARA A HOME
        $request->getRouter()->redirect('/');

    }

    /**
     * Método responsável por deslogar o usuário
     * @param Request $request
     */
    public static function setLogout($request)
    {

        //DESTROI A SESSÃO DE LOGIN
        SessionLogin::logout();

        //REDIRECIONA O USUÁRIO PARA A TELA DE LOGIN
        $request->getRouter()->redirect('/login');
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
    private static function getStatus($request)
    {
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();

        //STATUS
        if(!isset($queryParams['status'])) return '';

        //MENSAGEM DE STATUS
        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Usuário criado com sucesso! Por favor entre com seu e-mail e senha.');
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
                break;
            case 'invalid':
                return Alert::getError('E-mail ou senha inválido!');
                break;
            case 'duplicated':
                return Alert::getError('E-mail já está cadastrado, caso não lembre do acesso clique em esqueceu a senha.');
                break;
            case 'send':
                return Alert::getSuccess('E-mail de recuperação foi enviado com Sucesso!');
                break;
            case 'updateSuccess':
                return Alert::getSuccess('Senha atualizada com sucesso!');
                break;
            case 'confirmation':
                return Alert::getError('Senha e confirmação de senha são diferentes, tente novamente!');
                break;
            case 'notFound':
                return Alert::getError('O E-mail não foi localizado!');
                break;
            case 'changedToken':
                return Alert::getError('Este link será desativado! O link enviado parece ter sofrido alguma alteração, por isso não é possível fazer a verificação. Por favor solicite novamente a recuperação da senha.');
                break;
            case 'errorEmail':
                return Alert::getError('Erro ao enviar o e-mail');
                break;
        }
    }

}