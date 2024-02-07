<?
namespace Controller;
use Model\User;
use Controller\AbstractController;

class UserController extends AbstractController{
    private $user;
    public function handleRequest(){
        $this->user = new User();
        switch ($this->ressource){
            case "signup":
                if ($$this->user->signup($this->body)){
                    $result =  [ "statut" => 1,"message" =>  "Sign up succed"];
                }else{
                    $result =  [ "statut" => 0,"message" =>  "Sign up failed"];
                }
                echo json_encode($result);
            case "login" : 
                if ($this->user->login($this->param)){
                    $result =  [ "statut" => 1,"message" =>  "login succed"];
                }else{
                    $result =  [ "statut" => 0,"message" =>  "login failed.Login or password wrong"];    
                }
                echo json_encode($result);
            case "user" : 
                if (!empty($thisparam)){
                    $this->user->getUser($this->param["id"]);
                }else{
                    $this->user->getUser($this->param["id"]);
                }
                $result =  $this->user->getResult();
                echo json_encode($result);
        }
    }
}