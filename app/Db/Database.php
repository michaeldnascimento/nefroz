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

    /**
     * Nome do banco a ser manipulada
     * @var string
     */
    private $db;

    /**
     * Nome da tabela a ser manipulada
     * @var string
     */
    private $table;


    /**
     * Define a tabela e instancia e conexão
     * @param string|null $db
     * @param string|null $table
     */
    public function __construct($db = null, $table = null) {

        //RECEBE NOME DO BANCO E TABELA
        $this->db = $db;
        $this->table = $table;

        $config = new Config();
        $config->mysql['dbname'] = $this->db;
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
            //print_r($values);
            //$binds  = array_pad([],count($fields),'?');

            //MONTA A QUERY
            $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES (:' . implode(',:',$fields) . ')';

            //print_r($query);
            //exit;

            $this->setQuery($query);

            //MONTA O BIND COM OS CAMPOS ENVIADOS
            foreach ($fields as $field):
                 $this->bind(':'. $field, $values[$field]);
            endforeach;


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
        try {

            //DADOS DA QUERY
            $fields = array_keys($values);
            //$dados = array_values($values);

            //MONTAR OS PARAMETERS
            foreach ($fields as $v) {
                //$params[] = $v." = '".$dados[$k]."'";
                $params[] = $v."=:".$v;
            }

            //MONTA A QUERY
            $query = 'UPDATE '.$this->table.' SET '.implode(', ', $params).' WHERE '.$where;

            //EXECUTAR A QUERY
            $this->setQuery($query);

            //MONTA O BIND COM OS CAMPOS ENVIADOS
            foreach ($fields as $field):
                $this->bind(':'. $field, $values[$field]);
            endforeach;

            //EXECUTA O INSERT
            if ($this->execute()) {

                //RETORNA SE A QUERY FOI EXECUTADA
                return true;

            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
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