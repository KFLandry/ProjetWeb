<?php
namespace Model;
use PDO;
final class Donation extends AbstractModel {
    
    public function __construct(){
        parent::__construct("ed_donation");
    }
    public function create($data){
        //...
        $sql =  "";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute($data)){
            $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
            return  true;
        }else return false ; 
    }
}