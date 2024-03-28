<?php
namespace Model;

use Exception;
use Google\Cloud\Storage\StorageClient;
use PDO;
final class Media extends AbstractModel {
    private $cloudStorage;
    private $bucketName;
    private string $table_ass;
    private $field_ass;
    
    public function __construct(string $table_ass) {
        // Le fichier .env ne parse pas les blancs dans les structures donc je les remplace par des '%20' que je remplace ici par des espaces pour eviter de compromettre la clé
        // $file = json_decode($_ENV['GOOGLE_API_KEY'],true);
        // foreach($file as $property => $key){
        //     $file[$property] = str_replace('%20'," ",$key);
        // } ...
        $file["type"]=$_ENV["type"]; 
        $file["project_id"]=$_ENV["project_id"]; 
        $file["private_key_id"]=$_ENV["private_key_id"]; 
        $file["private_key"]=$_ENV['private_key']; 
        $file["client_id"]=$_ENV["client_id"]; 
        $file["client_email"]=$_ENV["client_email"]; 
        $file["auth_uri"]=$_ENV["auth_uri"]; 
        $file["token_uri"]=$_ENV["token_uri"]; 
        $file["auth_provider_x509_cert_url"]=$_ENV["auth_provider_x509_cert_url"]; 
        $file["client_x509_cert_url"]=$_ENV["client_x509_cert_url"]; 
        $file["universe_domain"]=$_ENV["universe_domain"]; 
        $this->cloudStorage = new StorageClient([
            'keyFile' => $file,
        ]);
        $this->bucketName = $_ENV['BUCKET_NAME'];
        // On gère la table associative passé en paramètre
        $this->table_ass = $table_ass;
        parent::__construct("ed_media");
        $x =  strlen($this->table_ass) - 3;
        $field =  substr($this->table_ass,-$x);
        $this->field_ass =  ucfirst($field);
    }
    public function get($id){
        try{
            $sql ="SELECT ed_media.id,ed_media.idItem,ed_media.category,ed_media.description,ed_media.location FROM ed_media JOIN $this->table_ass ON $this->table.id$this->field_ass = $this->table_ass.id WHERE $this->table.id$this->field_ass=$id ORDER BY id DESC ";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                $objet =  $this->cloudStorage->bucket($this->bucketName)->object($row['location']);
                $row['location'] =  $objet->signedUrl(new \DateTime('Tomorrow'));
                return $row;
            }else{
                return [];
            }
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        } 
    }
    public function storeImage($filePath, $name){
        try {
            $bucket  = $this->cloudStorage->bucket($this->bucketName);
            $name =  $_ENV['BUCKET_IMAGES'].$name; ;
            $bucket->upload(fopen($filePath,'r'), ['name' => $name]);
            return true;
        } catch (Exception $e ){
            var_dump($e);
        }
    }
    public function getAllObjets(){
        $bucket  = $this->cloudStorage->bucket($this->bucketName);
        foreach($bucket->objects() as $object){
            echo $object->signedUrl(new \DateTime('Tomorrow')).PHP_EOL;
        }
    }
    public function getAll(int $id){
        try{
            $sql ="SELECT * FROM $this->table WHERE id$this->field_ass=$id";
            $stmt= $this->con->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // On va chercher les  URL public de images dans le Cloud
            if (count($rows)> 0){
                for($i=0;$i<count($rows);$i++) {
                    $objet =  $this->cloudStorage->bucket($this->bucketName)->object($rows[$i]['location']);
                    $rows[$i]['location'] =  $objet->signedUrl(new \DateTime('Tomorrow'));
                }
            }
            return  $rows;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function moveMedia($idItem =null, $idUser =null, $nameInput = "files"){
        try{
            $bucket = $this->cloudStorage->bucket($this->bucketName);
            if (isset($_FILES["files"])){
                foreach ($_FILES["files"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES["files"]["tmp_name"][$key];
                        $name = basename($_FILES["files"]["name"][$key]);
                        if ($this->storeImage($tmp_name,$name)){
                            $data = Array();
                            $data['idUser'] =  $idUser;
                            $data['idItem'] = $idItem;
                            $data['name'] =  $name;
                            $data['category'] =  $nameInput;
                            $data['location'] = $_ENV['BUCKET_IMAGES'].$name;
                            $this->create($data);
                        }
                    } else {return false;}
                }return true;
            }else if (isset($_FILES[$nameInput])){
                $file = $_FILES[$nameInput];
                if ($file['error'] == UPLOAD_ERR_OK) {
                    $tmp_name = $file["tmp_name"];
                    $name = basename($file["name"]);
                    $this->storeImage($tmp_name,$name);
                    $data = Array();
                    $data['idUser'] =  $idUser;
                    $data['idItem'] = $idItem;
                    $data['name'] =  $name;
                    $data['category'] =  $nameInput;
                    $data['location'] = $_ENV['BUCKET_IMAGES'].$name;
                    if ($this->create($data)){
                        $this->result['newUrl'] = $bucket->object($data['location'])->signedUrl(new \DateTime('Tomorrow'));
                        return true;
                    }else{return false;}     
                }else{
                    return false;
                }
            }
        }catch(Exception $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data){
        try{
            $sql =  "INSERT INTO $this->table (idUser,idItem,name,category,location) VALUES (:idUser,:idItem,:name,:category,:location)";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([
                "idUser"=> $data['idUser'] =="" ? null : $data['idUser'],
                "idItem"=> $data['idItem'] =="" ? null : $data['idItem'],
                "name"=> $data["name"],
                "category"=> $data["category"], 
                "location"=> $data["location"]
            ]);
            $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
            return  true;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function update($data){
        try{
            //La mise a jour d'un media ici coonssite tout simplement a remplaver son contenu dans le cloud en gardant le même nom
            if (isset($_FILES[$data['name']])){
                if (!$_FILES[$data['name']]["error"] == UPLOAD_ERR_OK) { return false;}
                $filePath =$_FILES[$data['name']]["tmp_name"];
                $id =  $data['id'];
                $sql = "SELECT location FROM $this->table WHERE id=$id";                
                $stmt = $this->con->query($sql);
                $row  =  $stmt->fetch(PDO::FETCH_ASSOC);
                $bucket = $this->cloudStorage->bucket($this->bucketName);
                $bucket->upload(fopen($filePath,'r'), ['name' => $row['location']]);
                $this->result['newUrl'] = $bucket->object($row['location'])->signedUrl(new \DateTime('Tomorrow'));
                return true;
            }
       }catch(\PDOException $e){
           echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
           exit;
       }
    }
    public function delete($id){
        try{
            $sql = "SELECT * FROM ed_media WHERE id=$id";
            $stmt = $this->con->query($sql);
            $stmt =  $stmt->fetch(PDO::FETCH_ASSOC);
            $object  =  $this->cloudStorage->bucket($this->bucketName)->object($stmt['location']);
            if ($object->exists()){
                $object->delete();
            }
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}