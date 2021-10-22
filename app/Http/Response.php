<?php

namespace App\Http;

class Response{
    /**
     * Código do status HTTP
     * @param integer $httpCode
     */
    private $httpCode = 200;

    /**
     * Cabeçalho do Response
     * @param array $headers
     */
    private $headers = [];

    /**
     * Tipo de conteudo que está sendo retornado
     * @param string $contentType
     */
    private $contentType = 'text/html';

    /**
     * Conteudo do Response
     * @param mixed $content
     */
    private $content;

    /**
     * Métode respósavel por iniciar a classe e definir os valores
     * @param integer $httpCode
     * @param string $contentType
     * @param mixed $content
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {

        $this->httpCode    = $httpCode;
        $this->content     = $content;
        $this->setContentType($contentType);

    }

    /**
     * Método responsável por alterar o content type do response
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adicionar um registro no cabeçalho do response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar os headers para o navegador
     */
    private function sendHeaders()
    {
        //STATUS
        http_response_code($this->httpCode);

        //ENVIAR HEADERS
        foreach ($this->headers as $key=>$value){
            header($key. ': '.$value);
        }
    }

    /**
     * Método responsável por enviar a resposta para o usuário
     */
    public function sendResponse()
    {
        //ENVIA OS HEADERS
        $this->sendHeaders();

        //IMPRIME O CONTEUDO
        switch ($this->contentType) {
            case 'text/html';
                echo $this->content;
            exit;
            case 'application/json';
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }
    }
}