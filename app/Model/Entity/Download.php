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
        $this->id = (new Database('nefroz', 'downloads'))->insert([
            'name'  => $this->name,
            'email' => $this->email,
            'version' => $this->version,
            'date_download' => $this->date_download
        ]);

        //SUCESSO
        return true;
    }

}