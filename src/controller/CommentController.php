<?php
namespace Controller;
use Controller\AbstractController;
use Model\Comment;
class CommentController extends AbstractController {
    private $comment;
    public function handleRequest(){
        $this->comment = new Comment($this->body["table_ass"]);
        switch ($this->method){
            case "GET":
                if ($this->ressource =="comment"){
                    if ($this->id != 0){
                        $this->comment->get($this->id);
                    }else{
                        // $this->comment->getAll();
                    }
                    $this->result =  $this->comment->getResult();
                }
                break;

            case "POST":
                switch ($this->ressource){
                    case "updateComment":
                        if ($this->comment->update($this->body)){
                            $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                        }else{
                            $this->result = [ "statut"=> 0,"message"=> "Failed"];
                        };
                        break;
                }
                break;
            case "DELETE":
                if ($this->ressource == "comment"){
                    if ($this->comment->delete($this->id)){
                        $this->result = [ "statut"=> 1,"message"=> "Succeed"];
                    }else{
                        $this->result = [ "statut"=> 0,"message"=> "Failed"];
                    };
                }
            }
            echo json_encode($this->result);
    }
}