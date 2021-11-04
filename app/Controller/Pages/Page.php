<?php

namespace App\Controller\Pages;

//CHAMANDO O CS-ESTRUTURA DO SERVIDOR INTRANET
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cs-estrutura' . DIRECTORY_SEPARATOR . 'estrutura.php';
define("PROJETO", str_replace(ROOT_PATH, "", __DIR__));
use App\Core\Template;
use App\Core\Tools;

use App\Db\Pagination;
use App\Http\Request;
use \App\Utils\View;

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
        Template::$sistema = "Nefroz";
        Template::setSession(false);
        //Template::$idturma = [54, 12131];
        //Template::$nivel = 1;
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
     * Método responsável por retornar um link da paginação
     * @param array $queryParams
     * @param array $page
     * @param string $url
     * @return string
     */
    private static function getPaginationLink($queryParams, $page, $url, $label = null)
    {
        //ALTERA A PÁGINA
        $queryParams['page'] = $page['page'];

        //LINK
        $link = $url. '?' .http_build_query($queryParams);


        //VIEW
       return View::render('pages/pagination/link', [
            'page'   => isset($label) ? $label : $page['page'],
            'link'   => $link,
            'active' => $page['current'] ? 'active' : ''
        ]);
    }


    /**
     * Método responsavel por renderizar o layout de paginação
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination)
    {
        //PAGINAS
        $pages = $obPagination->getPages();


        //VERIFICA A QUANTIDADE DE PÁGINAS
        if(count($pages) <= 1) return '';

        //LINKS
        $links = '';

        //URL ATUAL (SEM GETS)
        $url = $request->getRouter()->getCurrentUrl();

        //GET PARAMETROS
        $queryParams = $request->getQueryParams();

        //PÁGINA ATUAL
        $currentPage = isset($queryParams['page']) ? $queryParams['page'] : 1;

        //LIMIT DA PÁGINAS
        $limit = getenv('PAGINATION_LIMIT');

        //MEIO DA PAGINAÇÃO
        $middle = ceil($limit/2);

        //INÍCIO DA PAGINAÇÃO
        $start = $middle > $currentPage ? 0 : $currentPage - $middle;

        //AJUSTA O FINAL DA PAGINAÇÃO
        $limit = $limit + $start;

        //AJUSTA O INÍCIO DA PAGINAÇÃO
        if($limit > count($pages)){
            $diff  = $limit - count($pages);
            $start = $start - $diff;
        }

        //LINKS INICIAL
        if($start > 0){
            $links .= self::getPaginationlink($queryParams, reset($pages),  $url, '<<');
        }


        //RENDERIZA OS LINKS
        foreach ($pages as $page){

            //VERIFICA O START DA PAGINAÇÃO
            if($page['page'] <= $start) continue;

            //VERIFICA O LIMITE DE PAGINAÇÃO
            if($page['page'] > $limit){
                $links .= self::getPaginationlink($queryParams, end($pages),  $url, '>>');
                break;
            }

            $links .= self::getPaginationlink($queryParams, $page,  $url);
        }

        //RENDERIZA BOX DE PAGINAÇÃO
        return View::render('pages/pagination/box', [
            'links'   => $links
        ]);
    }

    /**
     * Método responsavel por retornar o conteudo (view) da nossa home
     * @return string
     */
    public static function getPage($title, $content)
    {
        return View::render('pages/page', [
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
     * @param string $currentModule
     * @return string
     */
    public static function getPanel($title, $content, $currentModule)
    {

        //RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('login/panel', [
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        //RETORNA A PÁGINA RENDERIZADA
        return self::getPage($title,$contentPanel);

    }

}