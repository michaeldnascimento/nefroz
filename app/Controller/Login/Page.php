<?php

namespace App\Controller\Login;

//CHAMANDO O CS-ESTRUTURA DO SERVIDOR INTRANET
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cs-estrutura' . DIRECTORY_SEPARATOR . 'estrutura.php';
define("PROJETO", str_replace(ROOT_PATH, "", __DIR__));
use App\Core\Template;
use App\Core\Tools;

use \App\Utils\View;
use \App\Db\Pagination;
use \App\Http\Request;

class Page {


    /**
     * Método responsavel por renderizar o topo da pagina
     * @return string
     */
    private static function getHeader()
    {

        Template::$assets = [
            'jquery' => TRUE,
            'bootstrap' => TRUE,
            'font-awesome' => TRUE,
            'jquery-ui' => TRUE,
            'jquery-maskedinput' => TRUE,
            'jquery-maskmoney' => TRUE,
            'bootstrap-dialog' => TRUE,
            'toastr' => FALSE,
            'owl-carousel' => FALSE,
        ];

        Template::loadAssets();
        Template::setSession(false);
        Template::$sistema = "Nefroz";
        Template::getHeader();
        Template::getMenu();

        return View::render('pages/header');
    }

    /**
     * Método responsavel por renderizar o rodapé da pagina
     * @return string
     */
    private static function getFooter()
    {
        Template::getFooter();
        return View::render('pages/footer');
    }


    /**
     * Método responsável por retornar o conteúdo (view) da estrutura genética da página do painel
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage($title, $content)
    {
        return View::render('login/page', [
            'title'   => $title,
            'header'  => self::getHeader(),
            'content' => $content,
            'footer'  => self::getFooter()
        ]);
    }

    /**
     * Método responsável por rederizar a view do painel com conteúdos dinamicos
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPanel($title, $content, $currentModule)
    {

        //RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('login/panel', [
            //'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        //RETORNA A PÁGINA RENDERIZADA
        return self::getPage($title,$contentPanel);

    }


}