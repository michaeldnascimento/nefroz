<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;
use PDOException;
use PDOStatement;

class Recover {

    /**
     * ID do Usuário
     * @var integer
     */
    public $id;

    /**
     * E-mail do Usuário
     *  @var string
     */
    public $login;

    /**
     * Token do Usuário
     *  @var string
     */
    public $token;

    /**
     * Data Solicitacão
     *  @var string
     */
    public $date_recover;


    /**
     * Data Atualizacão solicitação
     *  @var string
     */
    public $date_update;


    /**
     * Status Solicitação
     *  @var integer
     */
    public $status;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return bool
     */
    public function cadastrar()
    {

        $this->date_recover = date('Y-m-d H:i:s');

        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('nefroz', 'recupera'))->insert([
            'login' => $this->login,
            'token' => $this->token,
            'date_recover' => $this->date_recover,
            'status' => 1
        ]);

        //echo $this->id;
        //exit;

        //SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return bool
     */
    public function atualizar()
    {

        $this->date_update = date('Y-m-d H:i:s');

        //ATUALIZA O DEPOIMENTO NO BANCO DE DADOS
        return (new Database('nefroz', 'recupera'))->update('id = '. $this->id, [
            'login'  => $this->login,
            'token' => $this->token,
            'status' => $this->status,
            'date_update' => $this->date_update
        ]);
    }

    /**
     * Método responsável por excluir um usuário do banco de dados
     * @return boolean
     */
    public function excluir()
    {
        //EXCLUI O DEPOIMENTO DO BANCO DE DADOS
        return (new Database('usuarios'))->delete('id = '.$this->id);
    }


    /**
     * Método responsável por retornar um usuário com base no seu ID
     *
     * @param integer $id
     * @return User
     */
    public static function getUserById($id)
    {
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }




    /**
     * Método responsavel por consultar se o token está valido
     * @param string $token
     * @return User
     */
    public static function tokenValidation($token)
    {

        //echo $token;
        //exit;

        return self::getRecover('token = "'. $token.'" AND status = 1');
        //return (new Database('usuarios'))->select('email = "'. $email.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar depoimentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getRecover($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('nefroz', 'recupera'))->select($where, $order, $limit, $fields);
    }

}