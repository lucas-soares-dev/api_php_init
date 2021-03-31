<?php
// OBS: ESTE ARQUIVO SEMPRE VAI SER CARREGADO QUANDO INICIAR A APLICAÇÃO


// RESPONSÁVEL POR EXIBIR QUALQUER ERRO
    // INIT_SET --> RESPONSÁVEL POR CRIAR VALIDAÇÕES SOBRE ERROS, WARNINGS, NOTICE ...
    // ESSES ERROS NUNCA DEVEM SER SETADOS EM PRODUÇÃO PARA O USUÁRIO, PORTANTO, TEM QUE COLOCAR O ZERO(0) NO LUGAR DO 1
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

// DEFININDO ALGUMAS CONSTANTES GLOBAIS 
define('HOST', 'localhost');
define('DBNAME', 'api-php');
define('USER', 'root');
define('PASSWORD', 'Admin@123*');

define('DS', DIRECTORY_SEPARATOR);
define('DIR_APP', __DIR__);
define('DIR_PROJETO', 'api_php_init');

if(file_exists('autoload.php')) {
    include 'autoload.php';
} else {
    echo 'Erro ao incluir o autoload.';exit;
}