<?php
namespace Model;
use PDO;
use PDOException;
final class Item extends AbstractModel {
    private $media;
    public function __construct(){
        parent::__construct("ed_item");
        $this->media = new Media('ed_item');
    }
    public function getItem($id){
        try{
            $sql ="SELECT * FROM $this->table WHERE id=$id";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $row['medias'] = $this->media->getAll($row['id']);
            $this->result =  $row;
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function getAll(){
        try{
            $sql ="SELECT * FROM $this->table";
            $stmt= $this->con->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $row['medias'] =  $this->media->getAll($row['id']);
            }   
            $this->result =  $rows;
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data){
        try{
            $sql =  "INSERT INTO $this->table (name,residence,worth,state,description,available,period,category) VALUES (:name,:residence,:worth,:state,:description,:available,:period,:category)";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute($data)){
                $sql = "SELECT * FROM ed_user WHERE id=LAST_INSERT_ID()";
                $stmt =  $this->con->query($sql);
                $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
                return  true;
            }
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}