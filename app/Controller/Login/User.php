<?php

namespace App\Controller\Login;

use App\Http\Request;
use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;

class User extends Page {


    /**
     * Método responsável por retornar a renderização a view de listagem de usuários
     * @param Request $request
     * @return string
     */
    public static function getUsers($request)
    {
        //CONTEÚDO DE HOME
        $content = View::render('admin/modules/users/index', [
            'itens'       => self::getUserItems($request, $obPagination),
            'pagination'  => parent::getPagination($request, $obPagination),
            'status'      => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Usuários > WDEV', $content, 'users');
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo depoimento
     * @param Request $request
     * @return string
     */
    public static function getNewUser($request)
    {
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/form', [
            'title'    => 'Cadastrar Usuário',
            'nome'     => '',
            'email'    => '',
            'status'   => self::getStatus($request)
        ]);

        return parent::getPanel('Cadastro usuário > WDEV', $content, 'users');
    }

    /**
     * Método responsável por cadastrar um depoimento no banco
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

        //VALIDA E-MAIL DO USUÁRIO
        $obUser = EntityUser::getUserByEmail($email);

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
                return Alert::getSuccess('Usuário criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O E-mail digitado já está sendo ultilizado por outro usuário!');
                break;
        }
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditUser($request, $id)
    {
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/form', [
            'title'    => 'Editar Usuário',
            'nome'     => $obUser->nome,
            'email'    => $obUser->email,
            'status' => self::getStatus($request)
        ]);

        return parent::getPanel('Editar usuário > WDEV', $content, 'users');
    }

    /**
     * Método responsável por grava a ataulização de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditUser($request, $id)
    {
        //OBTÉM O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        //POST VARS
        $postVars = $request->getPostVars();

        $email = isset($postVars['email']) ? $postVars['email'] : '';
        $nome  = isset($postVars['nome']) ? $postVars['nome'] : '';
        $senha = isset($postVars['senha']) ? $postVars['senha'] : '';

        //VALIDA E-MAIL DO USUÁRIO
        $obUserEmail = EntityUser::getUserByEmail($email);
        if ($obUserEmail instanceof EntityUser && $obUser->id != $id){
            //REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }

        //ATUALIZA A INSTANCIA
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha =  password_hash($senha, PASSWORD_DEFAULT);
        $obUser->atualizar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
    }


    /**
     * Método responsável por retornar o formulário de exclusão de um Usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getDeleteUser($request, $id)
    {
        //OBTÉM O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/delete', [
            'nome'     => $obUser->nome,
            'email'    => $obUser->email
        ]);

        return parent::getPanel('Excluir Usuário > WDEV', $content, 'users');
    }


    /**
     * Método responsável por excluir um Usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteUser($request, $id)
    {
        //OBTÉM O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        //EXCLUI O DEPOIMENTO
        $obUser->excluir();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users?status=deleted');
    }


}