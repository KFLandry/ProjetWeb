<?php
namespace Model;
use PDO;
class Comment extends AbstractModel {
    private $id;
    private $idUser;
    private $idItems;
    private $rate;
    private $message;
    private $like;
    private $reports;
    private string $table_ass;
    public function __construct($table_ass){
        $this->table_ass =  $table_ass;
        parent::__construct("ed_comment");
    }
    
    public function get($id){
        $x =  strlen($this->table_ass) - 3;
        $field =  substr($this->table_ass,-$x);
        $sql ="SELECT * FROM $this->table JOIN $this->table_ass ON $this->table.id$field = $this->table_ass.id$field WHERE $this->table.id$field=$id";
        $stmt= $this->con->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->result =  $row;
    }
    public function create($data){
        $sql =  "INSERT INTO $this->table (idUser,idItem,rate,message,datePublished,_like,report) VALUES (:idUser,:idItem,:rate,:message,:datePublished,:_like,:report)";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute($data)){
            $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
            return  true;
        }else return false ; 
    }       
}