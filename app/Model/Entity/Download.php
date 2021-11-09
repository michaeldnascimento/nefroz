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
     * ip do usuário
     *  @var string
     */
    public $ip;

    /**
     * pega dados do browser
     *  @var string
     */
    public $browser;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return bool
     */
    public function cadastrar()
    {

        //PEGA DATA
        $this->date_download = date('Y-m-d H:i:s');

        //PEGA IP USUÁRIO
        $this->ip = $_SERVER['REMOTE_ADDR'];

        //PEGA DADOS DO USUÁRIO
        $this->browser = $_SERVER['HTTP_USER_AGENT'];

        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('nefroz', 'downloads'))->insert([
            'name'  => $this->name,
            'email' => $this->email,
            'version' => $this->version,
            'date_download' => $this->date_download,
            'ip' => $this->ip,
            'browser' => $this->browser
        ]);

        //SUCESSO
        return true;
    }

}