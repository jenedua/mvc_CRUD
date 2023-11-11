<?php 
namespace App\Http;
use \Closure;
use \Exception;
use \ReflectionFunction;

class Router{
    /**
     * URL completa do projeto
     * @var string
     */
    private $url = '';
    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix ='';
    /**
     * Indice das rotas
     * @var array
     */
    private $routes = [];
    /**
     * Instancia de Request
     * @var Request
     */
    private $request;
    public function __construct($url){
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }
    /**
     * Méthodo responsavel por definir o prefixo das rotas
     *
     * @return void
     */
    private function setPrefix(){
        //informaçao do url atual
        $parseUrl = parse_url($this->url);
        //define o prefix
        $this->prefix = $parseUrl['path'] ?? '';

        
    }
    /**
     * Método responsavel por adicionar uma rota na classa
     *
     * @param string $methods
     * @param string $route
     * @param array $params
     */
    private  function addRoute($method, $route, $params=[]){

       //VALIDAÇÃO DOS PARAMETROS
       foreach($params as  $key=>$value){
        if($value instanceof Closure){
            $params['controller'] = $value;
            unset($params[$key]);
            continue;
        }
       }

       //VARIAVEIS DA ROTAS
       $params['variables']= [];

       //PADRÃO DE VALIDAÇÃO DAS VARIAVEIS DAS ROTAS
       $patternVariavel ='/{(.*?)}/';
       if(preg_match_all($patternVariavel,$route,$matches)){
            $route = preg_replace($patternVariavel, '(.*?)',$route);
            $params['variables'] = $matches[1];
       }


       //PADRÃO DE VALIDAÇÃO DO URL
       $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

       
        
      //ADICIONA A ROTA DENTRO DA CLASSE
      $this->routes[$patternRoute][$method] = $params;


    }
    /**
     * Método responsavel para uma rota GET
     * @param string $route
     * @param array  $params
     */
    public function get($route, $params =[]){
        return $this->addRoute('GET', $route, $params);


    }
    /**
     * Método responsavel para uma rota POST
     * @param string $route
     * @param array  $params
     */
    public function post($route, $params =[]){
        return $this->addRoute('POST', $route, $params);


    }
    /**
     * Método responsavel para uma rota PUT
     * @param string $route
     * @param array  $params
     */
    public function put($route, $params =[]){
        return $this->addRoute('PUT', $route, $params);


    }
    /**
     * Método responsavel para uma rota DELETE
     * @param string $route
     * @param array  $params
     */
    public function delete($route, $params =[]){
        return $this->addRoute('DELETE', $route, $params);


    }
    /**
     *Metodo responsavel por retorna a URI desconsiderando o prefixo
     *
     * @return string
     */
    private function getUri(){
        //URI da resquest
        $uri= $this->request->getUri();
        //fatia a URI com o prefix
        
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri): [$uri];
        //retornar a URI sem prefix
        return end($xUri);


    }
    /**
     *Metodo responsavel por executar os dados da rota atual
     *
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();
        //Method
        $httpMethod = $this->request->getHttpMethod();
        // valida as rotas
        foreach($this->routes as $patternRoute=>$methods){
            //verifica se a URI bate com padrão
            if(preg_match($patternRoute,$uri,$matches)){
                
                //verifica o metodo
                if(isset($methods[$httpMethod])){
                    //REMOVE A PRIMEIRA POSIÇÃO
                    unset($matches[0]);

                    //VARIABLES PROCESSADO
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                   
                    //retornos dos parametros da rota
                    return $methods[$httpMethod];
                }
                throw new Exception("Método não permitido", 405);
            }

        }
        //URL não encontrada
        throw new Exception("URL não encontrada",404);
        

        
    }
    /**
     * Método responsavel por executar a rotar atual
     *
     * @return Response
     */
    public function run(){
        try {
           // die('87');
            //throw new Exception("Página não encontrada", 504);
            //Obter a rota atual
            $route = $this->getRoute();
            
             
            //verifica o controlador
            if(!isset($route['controller'])){
                throw new Exception("A URL não pode ser processada", 500);
            }
            //ARGUMENTO DA FUNÇÃO
            $args =[];

            //REFLECTION
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter){
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
               
            }
            // echo "<pre>";
            // print_r($args);
            // echo "</pre>";
            // exit;

            return call_user_func_array($route['controller'],$args);
        }catch (Exception $e) {
            return new Response($e->getCode(),$e->getMessage());
        }

    }
 



}


