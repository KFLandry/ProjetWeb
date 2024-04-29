<?php
namespace Controller;
use Model\Historique\Historique;
use Model\JWT\JWT;
abstract class AbstractController{
    protected $ressource;
    protected $method;
    protected $body;
    protected $id;
    protected $historic;
    protected $jwt;
    protected $result;
    public function __construct(){
        $this->jwt= new JWT();
        $this->historic= new Historique();
        $uri =  explode("/",$_SERVER[$_ENV["PATHINFO"]]);
        $this->ressource  = $uri[1];
        // Je force le verbe a PATCH pour une mediaUpdate
        $this->method = $this->ressource !=="mediaUpdate" ? $_SERVER["REQUEST_METHOD"] : "PATCH" ;
        $this->body = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"),true) : $_REQUEST;
        $this->id = isset($_REQUEST['id']) ? ($_REQUEST['id']) : ((count($uri))> 2 ? $uri[2] : 0);//L'id =  identifiant compte || email compte
    }
    public abstract function handleRequest ();

}