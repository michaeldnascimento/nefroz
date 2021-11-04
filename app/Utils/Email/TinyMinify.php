<?php

namespace App\Utils\Email;

use App\Utils\Email\TinyHtmlMinifier;

class TinyMinify
{
    /**
     * Método responsável por minificar o e-mail
     * @param string $html
     * @param array $options
     * @return string
     */
    public static function html($html, $options = [])
    {
        $minifier = new TinyHtmlMinifier($options);
        return $minifier->minify($html);
    }
}