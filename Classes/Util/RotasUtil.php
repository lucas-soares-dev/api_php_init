<?php

namespace Util;

class RotasUtil
{    
    /**
     * DEFINICAO DE ROTAS E RECURSOS
     *
     * @return void
     */
    public static function getRotas()
    {
        $url = self::getUrls();

        $request = [];
        $request['rota'] = strtoupper($url[0]);
        $request['recurso'] = $url[1] ? filter_var($url[1], FILTER_SANITIZE_STRING) : null;
        $request['id'] = $url[2] ? filter_var($url[2], FILTER_SANITIZE_NUMBER_INT) : null;
        $request['metodo'] = $_SERVER['REQUEST_METHOD']; // TIPO DA REQUISIÇÃO

        return $request;
    }
    
    /**
     * getUrls
     *
     * @return void
     */
    public static function getUrls()
    {   
        // SUBSTITUINDO O /API_PROJ POR VAZIO PARA TRATAR AS ROTAS
        // REQUEST_URI - ARMAZENA A URL DA REQUISIÇÃO
        $uri = str_replace('/'.DIR_PROJETO, '', $_SERVER['REQUEST_URI']);

        return explode('/', trim($uri, '/')); // EXPLODINDO A URL DA REQUEST ONDE CONTÉM UMA ( / ), PARA RETORNAR SOMENTE AS ROTAS
    }
}