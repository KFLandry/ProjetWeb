<?php 
namespace Controller;
use Controller\AbstractController;
use Model\Item;

class ItemController extends AbstractController{
    private $item;
    public function handleRequest(){
        $this->item = new Item();
        switch ($this->method){
            case "GET":
                switch ($this->ressource){
                    case "item" : 
                        if ($this->id != 0){
                            $this->item->get($this->id);
                        }else{
                            $this->item->getAll();
                        }
                        $this->result =  $this->item->getResult(); 
                        break;
                    case "items":
                        if( $this->id != 0){
                            $this->item->getAll($this->id);  
                            $this->result = $this->item->getResult();
                        }
                        break;
                }
                break;
            case "POST":
                switch ($this->ressource){
                    case "item":
                        $this->item->create($this->body);
                        $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                        break;
                    case "updateItem":
                        if ($this->item->update($this->body)){
                            $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                        }else{
                            $this->result = [ "statut"=> 0,"message"=> "Failed"];
                        };
                        break;
                }
                break;
            case "DELETE":
                if ($this->ressource == "item"){
                    if ($this->item->delete($this->id)){
                        $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                    }else{
                        $this->result = [ "statut"=> 0,"message"=> "Failed"];
                    };
                }
            }
            echo json_encode($this->result);
    }
}