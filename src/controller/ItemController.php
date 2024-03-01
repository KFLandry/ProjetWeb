<?php 
namespace Controller;
use Controller\AbstractController;
use Model\Donation;
use Model\Item;

class ItemController extends AbstractController{
    private $item;
    private $donation;
    public function handleRequest(){
        $this->item = new Item();
        $this->donation = new Donation();
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
                        $this->result = $this->item->update($this->body) ? [ "statut"=> 1,"message"=> "Succeed"] :[ "statut"=> 0,"message"=> "Failed"];
                        break;
                    case "recover":
                        $this->donation->create($this->body);
                        $fields = Array();
                        $fields["id"] = $this->body['idItem'];
                        $fields["statut"] = "En attente de validation";
                        $this->result = $this->item->update($fields) ? [ "statut"=> 1,"message"=> "Succeed"] :[ "statut"=> 0,"message"=> "Failed"];
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