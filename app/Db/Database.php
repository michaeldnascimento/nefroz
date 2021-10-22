<?php

namespace App\Db;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cs-estrutura' . DIRECTORY_SEPARATOR . 'estrutura.php';
use App\Core\DB;
use App\Core\Tools;
use App\Config\Config;

use \PDO;
use \PDOException;
use PDOStatement;

class Database extends DB{

    private $table = "usuarios";

    public function __construct() {
        $config = new Config();
        $config->mysql['dbname'] = 'nefroz';
        parent::__construct($config->mysql);
    }

    /**
     * Método responsável por inserir dados no banco
     * @param  array $values [ field => value ]
     * @return integer ID inserido
     */
    public function insert($values){
        try {
            //DADOS DA QUERY
            $fields = array_keys($values);
            //$binds  = array_pad([],count($fields),'?');

            //MONTA A QUERY
            $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES (:nome, :email, :senha, :created_date, :updated_date)';

            $this->setQuery($query);

            //RESGATA OS VALORES
            $this->bind(':nome', $values['nome']);
            $this->bind(':email', $values['email']);
            $this->bind(':senha', $values['senha']);
            $this->bind(':created_date', $values['created_date']);
            $this->bind(':updated_date', $values['updated_date']);

            //EXECUTA O INSERT
            if ($this->execute()) {

                //RETORNA O ID INSERIDO
                return $this->lastInsertId();

            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Método responsável por executar uma consulta no banco
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @param  string $fields
     * @return bool
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        try {
        //DADOS DA QUERY
        $where = strlen($where) ? 'WHERE '.$where : '';
        $order = strlen($order) ? 'ORDER BY '.$order : '';
        $limit = strlen($limit) ? 'LIMIT '.$limit : '';

        //MONTA A QUERY
        $this->setQuery('SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit);

        if ($this->execute()) {
            //EXECUTA A QUERY
            return $this->single();

        } else {
            return false;
        }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Método responsável por executar atualizações no banco de dados
     * @param  string $where
     * @param  array $values [ field => value ]
     * @return boolean
     */
    public function update($where,$values){
        //DADOS DA QUERY
        $fields = array_keys($values);

        //MONTA A QUERY
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;

        //EXECUTAR A QUERY
        $this->execute($query,array_values($values));

        //RETORNA SUCESSO
        return true;
    }

    /**
     * Método responsável por excluir dados do banco
     * @param  string $where
     * @return boolean
     */
    public function delete($where){
        //MONTA A QUERY
        $query = 'DELETE FROM '.$this->table.' WHERE '.$where;

        //EXECUTA A QUERY
        $this->execute($query);

        //RETORNA SUCESSO
        return true;
    }

}