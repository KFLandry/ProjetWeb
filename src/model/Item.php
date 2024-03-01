<?php
namespace Model;
use PDO;
use PDOException;
final class Item extends AbstractModel {
    private $media;
    private $user;
    public function __construct(){
        parent::__construct("ed_item");
        $this->media = new Media('ed_item');
        $this->user =  new User();
    }
    public function get($id){
        try{
            $sql ="SELECT * FROM $this->table WHERE id=$id";
            $stmt= $this->con->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $medias  =  $this->media->getAll($row['id']);
            $row['medias'] = $medias;
            $publisher =  $this->user->get($row['idUser'],true);
            $row['publisher'] = $publisher; 
            $this->result =  $row;
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function getAll($idUser = 0){
        try{
            $sql = $idUser == 0 ? "SELECT * FROM $this->table" : "SELECT * FROM $this->table WHERE idUser=$idUser";
            $stmt= $this->con->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($rows); $i++){
                $rows[$i]['medias'] =  $this->media->getAll($rows[$i]['id']);
                 $publisher =  $this->user->get($rows[$i]['idUser'],true);
                 $rows[$i]['publisher'] = $publisher; 
            }
            $this->result =  $rows;
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
    public function create($data){
        // Je insère l'annonce en base et je déplace les images vers le repertoire des images de serveurs
        try{
            $sql =  "INSERT INTO $this->table (name,residence,worth,state,description,available,period,category) VALUES (:name,:residence,:worth,:state,:description,:available,:period,:category)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':residence', $data['residence']);
            $stmt->bindParam(':worth', $data['worth']);
            $stmt->bindParam(':state', $data['state']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':period', $data['period']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':available', $data['available']);
            $stmt->bindParam(':publishedDate', date("Y-m-d"));

            if ($stmt->execute()){
                $sql = "SELECT * FROM ed_item WHERE id=LAST_INSERT_ID()";
                $stmt =  $this->con->query($sql);
                $this->result = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->media->moveMedia($this->result['id'],"","");
                return  true;
            }
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}