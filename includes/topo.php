<?php
//CHAMANDO O CS-ESTRUTURA DO SERVIDOR INTRANET
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cs-estrutura' . DIRECTORY_SEPARATOR . 'estrutura.php';

use App\Core\Template;
use App\Core\Tools;

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

//Template::setSession(true);
Template::$sistema = "Nefroz";
Template::loadAssets();
Template::getHeader();
Template::getMenu();


//echo "<pre>";
//print_r($_SESSION);
//echo $_SESSION['usuario'];
//echo Tools::estaLogado() . "<br>";
//exit;
//echo Tools::getlogin() . "<br>";
//echo Tools::getLogin2() . "<br>";
//echo Tools::getNome() . "<br>";