<?php
namespace Model;
use PDOException;
use PDO;
final class Donation extends AbstractModel {
    
    public function __construct(){
        parent::__construct("ed_donation");
    }
    public function create($data){
        try{
            $sql =  "INSERT INTO ed_donation (message, idItem, idUser) VALUES (:message, :idItem, :idUser)";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute($data)){
                $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
                $stmt =  $this->con->query($sql);
                $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
                return  true;
            }else return false ; 
        }catch(PDOException $e){
            echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
            exit;
        }
    }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       