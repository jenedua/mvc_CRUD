<?php 
namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;

class Testimony extends Page{
    /**
     * Método responsavel  por obter a renderização dos itens do depoimentos para a pagina
     * @return string
     */

    private static function getTesmonyItems(){
        //DEPOIMENTOS
        $itens = '';

        //RESULTADO DA PAGINA
        $result = EntityTestimony::getTestimonies(null, 'id DESC');

        //RENDERIZA O ITEN
        while($obTestimony =$result->fetchObject(EntityTestimony::class)){
           $itens.= View::render('pages/testimony/item', [
              'nome' => $obTestimony->nome,
              'mensagem' => $obTestimony->mensagem,
              'data' => date('d/m/Y H:i:s',strtotime($obTestimony->data))


            ]);
        // echo "<pre>";
        // print_r($obTestimony);
        // echo "</pre>";
        // exit;

        }


        //RETORNA OS DEPOIMENTOS
        return $itens;

    }

    
    public static function getTestimonies(){
        /**
         * Método responsavel por retorna a conteudo (view) de depoimentos
         * @return string 
         */
       // $organization = new Organization;
        
        $content = View::render('pages/testimonies',[
            'itens' => self::getTesmonyItems()
          
            
        ]);

        return parent::getPage('FDEV > DEPOIMENTOS ', $content);

    }
    /**
     * Metodo responsavel por um depoimento
     *
     * @param  $request
     * @return string
     */
    public static function insertTestimony($request){
        //DADOS DO POST
        $postVars = $request->getPostVars() ;
        //NOVA INSTANCIA DE DEPOIMENTO
        $obTestimony = new EntityTestimony();
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();
       

        return self::getTestimonies();

    }






}



?>