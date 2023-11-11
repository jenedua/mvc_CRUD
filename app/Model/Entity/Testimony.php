<?php
namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony{
    public $id;
    public $nome;
    public $mensagem;
    public $data;

    /**
     * Metodo responsavel por cadastrar a instancia atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar(){
        //DEFINE A DATA
        $this->data = date('Y-m-d H:i:s');
        // INSERE O DEPOIMENTO NO BANCO DE DADOS

        $this->id = (new Database('depoimentos'))->insert([
            'nome'=> $this->nome,
            'mensagem'=> $this->mensagem,
            'data'=> $this->data

        ]);
        // echo"<pre>";
        //     print_r($this);
        // echo"</pre>";exit;
        //SUCESSO
        return true;

    }
    /**
     * Método responsavel por retorna depoimentos
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $field
     * @return \PDOStatement
     */
    public static function getTestimonies($where=null, $order=null, $limit=null, $fields='*'){
        return (new Database('depoimentos'))->select($where,$order,$limit,$fields);

    }


}



