<?php

namespace App\Http\Middleware;
use App\Http\Request;
use App\Http\Response;
use Closure;
use Exception;

class Queue {

    /**
     * Mapeamento de middleware
     * @param array $map
     */
    private static $map = [];

    /**
     * Mapeamento de middleware que serão carregados em todas as rotas
     * @param array $default
     */
    private static $default = [];

    /**
     * Fila de middlewares a serem executados
     * @param array $middlewares
     */
    private $middlewares = [];

    /**
     * Função de execução do controlador
     * @param Closure $controller
     */
    private $controller;


    /**
     * Argumentos da função do controlador
     * @param array $controllerArgs
     */
    private $controllerArgs = [];

    /**
     * Método responsável por construir a classe de fila de middleware
     * @param array $middlewares
     * @param Closure $controller
     * @param array $controllerArgs
     */
    public function __construct($middlewares, $controller, $controllerArgs)
    {
        $this->middlewares    = array_merge(self::$default,$middlewares);
        $this->controller     = $controller;
        $this->controllerArgs = $controllerArgs;
    }


    /**
     * Método responsável por definir o mapeamento de middlewares
     * @param array $map
     */
    public static function setMap($map)
    {
        self::$map = $map;
    }

    /**
     * Método responsável por definir o mapeamento de middlewares padrão em todas as rotas
     * @param array $default
     */
    public static function setDefault($default)
    {
        self::$default = $default;
    }

    /**
     * Método responsável por executar o próximo nivel da fila de middlewares
     * @param Request $request
     * @throws Exception
     * @return Response
     */
    public function next($request)
    {

        //VERIFICA SE A FILA ESTÁ VAZIA
        if(empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);

        //MIDDLEWARE
        $middleware = array_shift($this->middlewares);

        //VERIFICA O MAPEAMENTO
        if(!isset(self::$map[$middleware])){
            throw new Exception("Problemas ao precessar o middleware da requisição", 500);
        }

        //NEXT
        $queue = $this;
        $next = function($request) use($queue){
            return $queue->next($request);
        };

        //EXECUTA O MIDDLEWARE
        return (new self::$map[$middleware])->handle($request,$next);

//        echo "<pre>";
//       print_r($next);
//        echo "<pre>";
////        print_r($this);
//        exit;
    }


}