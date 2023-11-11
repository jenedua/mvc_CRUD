<?php
require __DIR__.'/../vendor/autoload.php';


use App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;

//VARIABLES DE AMBIENTE
Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇÕES DO BANCO
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')

);

// echo"
//<pre>";
//     print_r(getenv('URL'));exit;
// echo "</pre>";

define('URL', getenv('URL'));

//DEFINE O VALOR PADRÃO DAS PAGINAS
View::init([
'URL' => URL
]);