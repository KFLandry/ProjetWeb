<?php
namespace Model;

use Exception;
use Google\Cloud\Storage\StorageClient;
use PDO;
final class Media extends AbstractModel {
    private $storage;
    private $bucketName;
    private string $table_ass;
    private $field_ass;
    
    public function __construct(string $table_ass) {
        $this->storage = new StorageClient([
            'projectId' => $_ENV['PROJECT_ID'],
            'keyFIlePath' => $_ENV['KEYFILEPATH']
        ]);
        $this->bucketName = "bucket_educycle";
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
            return $row;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        } 
    }
    // 
    public function storeImage(){
        try {
            $bucket  = $this->storage->bucket($this->bucketName);
            $filetoupload ="ressources/IMG_3771 - Copy.png";
            $bucket->upload(fopen($filetoupload,'r'),
                [
                    'name' => 'bucket_educycle/ressources/IMG_3771 - Copy.png'
                ]
            );
            echo 'Le fichier a été uploadée'.PHP_EOL;
            $objectUploaded =  $bucket->object('bucket_educycle/ressources/IMG_3771 - Copy.png');
            var_dump(($objectUploaded));
        } catch (Exception $e ){
            var_dump($e);
        }
    }
    public function uploadFIle(){
        $bucket  = $this->storage->bucket($this->bucketName);
        $bucket->objects();
        var_dump($bucket);
    }
    public function getAll(int $id){
        try{
            $sql ="SELECT * FROM $this->table WHERE id$this->field_ass=$id";
            $stmt= $this->con->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return  $rows;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function moveMedia($idItem =null, $idUser =null, $nameInput = "files",$idMedia=0){
        try{
            $uploads_dir = $_ENV['CHEMIN_IMAGES'];
            if (isset($_FILES["files"])){
                foreach ($_FILES["files"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES["files"]["tmp_name"][$key];
                        $name = basename($_FILES["files"]["name"][$key]);
                        move_uploaded_file($tmp_name, "$uploads_dir/$name");
                        $data = Array();
                        $data['idUser'] =  $idUser;
                        $data['idItem'] = $idItem;
                        $data['name'] =  $name;
                        $data['category'] =  $nameInput;
                        $data['location'] = "$uploads_dir/$name";
                       return  $this->create($data);
                    } else {
                        return false;
                    }
                }
            }else if (isset($_FILES[$nameInput])){
                $file = $_FILES[$nameInput];
                if ($file['error'] == UPLOAD_ERR_OK) {
                    $tmp_name = $file["tmp_name"];
                    $name = basename($file["name"]);
                    move_uploaded_file($tmp_name, "$uploads_dir/$name");
                    if ($idMedia>0){
                        $sql = "UPDATE $this->table SET location = '$uploads_dir/$name' WHERE id=$idMedia";
                        $stmt = $this->con->prepare($sql);
                        $stmt->execute();
                    }else{
                        $data = Array();
                        $data['idUser'] =  $idUser;
                        $data['idItem'] = $idItem;
                        $data['name'] =  $name;
                        $data['category'] =  $nameInput;
                        $data['location'] = "$uploads_dir/$name";
                        return $this->create($data);
                    }
                }else{
                    return false;
                }
            }
        }catch(\Exception $e){
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
            // Avant de mettre a jour un media on supprime celui qui existe dabord
            $id =  $data['id'];
            $sql = "SELECT * FROM $this->table WHERE id=$id";
            $stmt = $this->con->query($sql);
            $stmt =  $stmt->fetch(PDO::FETCH_ASSOC);
            if (file_exists($stmt['location'])){
                if(unlink($stmt['location'])){
                    $this->moveMedia("","",$data['name'], $id);
                }         
            }else{
                $this->moveMedia("","",$data['name'], $id);
            }
            return true;
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
            if(unlink($stmt['location'])){
                $sql = "DELETE FROM $this->table WHERE id=$id";
                $stmt = $this->con->prepare($sql);
                $stmt->execute();
            }
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}