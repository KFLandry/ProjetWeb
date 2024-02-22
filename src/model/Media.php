<?php
namespace Model;
use PDO;
final class Media extends AbstractModel {
    private string $table_ass;
    private $field_ass;
    
    public function __construct(string $table_ass) {
        $this->table_ass = $table_ass;
        parent::__construct("ed_media");
        $x =  strlen($this->table_ass) - 3;
        $field =  substr($this->table_ass,-$x);
        $this->field_ass =  ucfirst($field);
    }
    public function get($id){
        try{
            $sql ="SELECT ed_media.id,ed_media.idItem,ed_media.category,ed_media.description,ed_media.location FROM ed_media JOIN $this->table_ass ON $this->table.id$this->field_ass = $this->table_ass.id WHERE $this->table.id$this->field_ass=$id";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        } 
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
    public function moveMedia($idItem,$category){
        $uploads_dir = 'ressources\images';
        foreach ($_FILES["files"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["files"]["tmp_name"][$key];
                $name = basename($_FILES["files"]["name"][$key]);
                move_uploaded_file($tmp_name, "$uploads_dir/$name");
                $data = Array();
                $data['idUser'] =  $_POST['idUser'];
                $data['idItem'] = $idItem;
                $data['name'] =  $name;
                $data['category'] =  $category;
                $data['location'] = "$uploads_dir/$name";
                $this->create($data);
        }
    }  
    }
    public function create($data){
        try{
            $sql =  "INSERT INTO $this->table (idUser,idItem,name,category,location) VALUES (:idUser,:idItem,:name,:category,:location)";
            $stmt = $this->con->prepare($sql);
            $stmt->execute($data);
            $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
            return  true;
        }catch(\PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}