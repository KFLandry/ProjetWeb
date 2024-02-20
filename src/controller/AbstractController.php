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
        $uri =  explode("/",$_SERVER["REQUEST_URI"]);
        $this->ressource  = $uri[1];
        $this->method = $_SERVER["REQUEST_METHOD"];
        $body  = json_decode(file_get_contents("php://input"),true);
        $this->body  = $body;
        $this->id = count($uri)> 2 ? $uri[2] : 0;
    }
    public abstract function handleRequest ();

}