<?php

use \App\Controller\Pages;
use \App\Http\Response;

//ROTA HOME
$obRouter->get('/', [
    function () {
        return new Response(200,Pages\Home::getHome());
    }
]);

//ROTA SOBRE
$obRouter->get('/sobre', [
    function () {
        return new Response(200, Pages\About::getAbout());
    }
]);
//ROTA DEPOIMENTO 
$obRouter->get('/depoimentos', [
    function () {
        return new Response(200, Pages\Testimony::getTestimonies());
    }
]);
//ROTA DEPOIMENTO (INSERT)
$obRouter->post('/depoimentos', [
    function ($request) {
        // echo "<pre>";
        // print_r($request);exit;
        // echo "</pre>";
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);


//ROTA DINAMICA
// $obRouter->get('/pagina/{idPagina}/{acao}', [
//     function ($idPagina,$acao) {
//         return new Response(200, ' pagina'. $idPagina.' -'.$acao);
//     }
// ]);





