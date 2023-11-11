<?php
require __DIR__ .'/includes/app.php';

use \App\Http\Router;


//use App\Http\Response;

//  $obResponse = new Response('400', 'Olá, mundo');
//  $obResponse->sendResponse();

// echo "<pre>";
//     print_r($obResponse);
// incluir as rotas das paginas
$obRouter = new Router(URL);

include __DIR__.'/routes/pages.php';

//IMPRIMIR I RESPONSE DA ROTA
$obRouter->run()
         ->sendResponse();







?>