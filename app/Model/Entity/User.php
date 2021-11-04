<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;
use PDOException;
use PDOStatement;

class User {

    /**
    * ID do Usuário
    * @var integer
    */
    public $id;

    /**
     * Nome do Usuário
     * @var string
     */
    public $nome;

    /**
     * E-mail do Usuário
     *  @var string
     */
    public $email;

    /**
    * Senha do Usuário
     *  @var string
    */
    public $senha;

    /**
     * Senha do Usuário
     *  @var string
     */
    public $created_date;

    /**
     * Senha do Usuário
     *  @var string
     */
    public $updated_date;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return bool
     */
    public function cadastrar()
    {

        $this->created_date = date('Y-m-d H:i:s');
        $this->updated_date = date('Y-m-d H:i:s');

        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('nefroz','usuarios'))->insert([
            'nome'  => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
            'created_date' => $this->created_date,
            'updated_date' => $this->updated_date
        ]);

        //SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return bool
     */
    public function atualizar()
    {

        $this->updated_date = date('Y-m-d H:i:s');

        //ATUALIZA O DEPOIMENTO NO BANCO DE DADOS
        return (new Database('nefroz','usuarios'))->update('id = '. $this->id, [
            'nome'  => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
            'updated_date' => $this->updated_date
        ]);
    }

    /**
     * Método responsável por excluir um usuário do banco de dados
     * @return boolean
     */
    public function excluir()
    {
        //EXCLUI O DEPOIMENTO DO BANCO DE DADOS
        return (new Database('nefroz','usuarios'))->delete('id = '.$this->id);
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
     * Método responsavel por retornar um usuário com base em seu e-mail
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email)
    {

        return self::getUsers('email = "'. $email.'"');
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
    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('nefroz','usuarios'))->select($where, $order, $limit, $fields);
    }

}