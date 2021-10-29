<?php

namespace App\Model\Entity;

use \App\Db\Database;
use \PDO;
use PDOException;
use PDOStatement;

class Download {

    /**
     * ID do Download
     * @var integer
     */
    public $id;

    /**
     * Nome do Usuário
     * @var string
     */
    public $name;

    /**
     * E-mail do Usuário
     *  @var string
     */
    public $email;

    /**
     * Data download do Usuário
     *  @var string
     */
    public $date_download;

    /**
     * versão do download
     *  @var string
     */
    public $version;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return bool
     */
    public function cadastrar()
    {

        $this->date_download = date('Y-m-d H:i:s');

        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('downloads'))->insert([
            'name'  => $this->name,
            'email' => $this->email,
            'version' => $this->version,
            'date_download' => $this->date_download
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
        //ATUALIZA O DEPOIMENTO NO BANCO DE DADOS
        return (new Database('usuarios'))->update('id = '. $this->id, [
            'nome'  => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
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
        return (new Database('usuarios'))->select($where, $order, $limit, $fields);
    }

}