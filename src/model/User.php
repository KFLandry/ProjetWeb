<?php
namespace Model;
use PDO;

class User extends AbstractModel{
    private $id;
    private $lastName;
    private $firstName;
    private $email;
    private  $role;
    private  $phone;
    private $password;
    private $birthday;
    private $media;

    public function __construct(){
        Parent::__construct("ed_user");
        $this->media = new Media('ed_user');
    }
    public function get(int $id,bool $restrict = false){
        try{
            // Mysql ne prend pas en compte les FULL JOIN par consequent on fait une union d'une d'une LEFT JOIN  et d'une RIGHT JOIN
            $sql ="SELECT *
            FROM ed_user
            LEFT JOIN ed_media ON ed_user.id = ed_media.idUser
            WHERE ed_user.id = $id
            UNION
            SELECT *
            FROM ed_user
            RIGHT JOIN ed_media ON ed_user.id = ed_media.idUser
            WHERE ed_user.id = $id;
            ";
            
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row){
                $this->result =  $row;
                if ($row['id']) {
                    $this->result['media'] = $this->media->get($row['id']);
                }
                // Je suppime de mdp
                $this->result['password'] = "";
                if (!$restrict){
                    $this->result =  $row; 
                }else{ 
                    $result = [];
                    $result['id'] =  $row['id'];
                    $result['name'] = $row['firstName'].' '.$row['lastName'];
                    return $result;
                }
            }else $this->result = [];
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function getAll(){}
    public function login($credentials) : bool{
        try{
            $login  =  $credentials['login'];
            $password = $credentials['password'];
            $sql = "SELECT * FROM ed_user WHERE email=?";
            $query = $this->con->prepare($sql);
            $query->bindParam(1, $login, PDO::PARAM_STR);
            $query->execute();
            $row =  $query->fetch(PDO::FETCH_ASSOC);
            if ($row){
                if (password_verify($password.SALTING_KEY,$row["password"])){
                    $this->result =  $row;
                    // Je suppime de mdp
                    $this->result['password'] = "";
                    if ($row['id']) {
                        $this->result['media'] = $this->media->get($row['id']);
                    }
                    return true;
                }
            }
            return false;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function update($data){
        try{
            $dataString = "";
            foreach ($data as $key => $value) {
                if ($key != "id") {
                    //Si parmis les informations à mettre à jour il y'a le mot de passe on le hashe avant.
                    if ($key == "password"){
                        $value =password_hash($value.SALTING_KEY, PASSWORD_DEFAULT);
                    }
                    $dataString .= $key.'=\''.$value."', ";;
                }
            }
            $dataString = rtrim($dataString, ", "); 
            $id =  $data["id"];
            $sql = "UPDATE ed_user SET $dataString WHERE id=$id";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute();
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data) : bool {
        try{
            $sql =  "INSERT INTO ed_user (role,firstName,lastName,email,phone,birthday,password) VALUES (:role,:firstName,:lastName,:email,:phone,:birthday,:password);";
            $stmt =  $this->con->prepare($sql);
            //On hashe le mot de passe avant de l'enregistrer avec une cle de sallage personnalisée
            $password =  $data["password"].SALTING_KEY;
            $data["password"] =  password_hash($password,PASSWORD_DEFAULT); 
            $stmt->execute($data);
            $sql = "SELECT * FROM ed_user WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result = $stmt->fetch(PDO::FETCH_ASSOC);
            return  true;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    private function fillUser($row){
        $this->id  =  $row["id"];
        $this->lastName  =  $row["lastName"];
        $this->firstName  =  $row["firstName"];
        $this->birthday  =  $row["birthday"];
        $this->email  =  $row["email"];
        $this->phone  =  $row["phone"];
        $this->password  =  $row["password"];
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function __destruct(){}
}