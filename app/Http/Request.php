<?php

namespace App\Http;

class Request {

    /**
     * Intancia do Router
     * @var Router
     */
    private $router;

    /**
     * Método HTTP da requisicao
     * @param string $httpMethod
     */
    private $httpMethod;

    /**
     * URI da página
     * @param string $uri
     */
    private $uri;

    /**
     * Parametros da URL ($_GET)
     * @param array $queryParams
     */
    private $queryParams = [];

    /**
     * Variaveis recebidas no POST da página ($_POST)
     * @param array $postVars
     */
    private $postVars = [];

    /**
     * cabeçalho de requisição
     * @param array $headers
     */
    private $headers = [];

    // CONTRUTOR DA CLASSE
    public function __construct($router){
        $this->router      = $router;
        $this->queryParams = isset($_GET) ? $_GET : [];
        //$this->postVars    = $_POST ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        //$this->uri         = $_SERVER['REQUEST_URI'] ?? '';
        $this->setUri();
        $this->setPostVars();
    }

    /**
     * Método responsável por definir as variáveis do POST
     */
    private function setPostVars()
    {
        //VERIFICA O MÉTODO DA REQUISIÇÃO
        if($this->httpMethod == 'GET') return false;

        //POST PADRÃO
        $this->postVars = isset($_POST) ? $_POST : [];

        //POST JSON
        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }


    /**
     * Método responsável por definir a URI
     */
    public function setUri()
    {
        //URI COMPLETA (COM GET)
        $this->uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        //REMOVE GETS DA URI
        $xURI = explode('?', $this->uri);
        $this->uri = $xURI[0];
    }

    /**
     * Método responsável por retornar a instancia de Router
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Método responsável por retornar o método HTTP da requisição
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Método responsável por retornar o método URI da requisição
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Método responsável por retornar o método headers da requisição
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Método responsável por retornar o os parametros da URL da requisição
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Método responsável por retornar o os variaveis POST da requisição
     * @return array
     */
    public function getPostVars()
    {
        return $this->postVars;
    }


}