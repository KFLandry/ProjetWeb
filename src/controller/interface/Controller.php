<?php
namespace Controller;
abstract class AbstractController{
    protected $ressource;
    protected $body;
    protected $param;
    public function __construct(){
        $this->ressource  =  $_SERVER["REQUEST_URI"];
        
        $this->body  = file_get_contents("php:\\input");
        switch ($_SERVER["REQUEST_METHOD"]){
            case "GET":
                $this->param =  $_GET["param"];
            case "POST":
                $this->param =  $_GET["param"];
        }
    }
    public abstract function handleRequest ();
}