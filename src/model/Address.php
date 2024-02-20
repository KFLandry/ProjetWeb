<?php
namespace Model;
use PDO;

class Address extends AbstractModel {
    private $id;
    private $road;
    private $name;
    private $postal;
    private $city;
    private $country;
    private $default;
    private string $table_ass;
    
    public function __construct(string $table_ass) {
        $this->table_ass = $table_ass;
        parent::__construct("ed_media");
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
        $sql =  "INSERT INTO $this->table (idUser,idMeeting,road,name,city,postal,country) VALUES (:idUser,:idMeeting,:road,:name,:city,:postal,:country)";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute($data)){
            $sql = "SELECT * FROM $this->table WHERE id=LAST_INSERT_ID()";
            $stmt =  $this->con->query($sql);
            $this->result =  $stmt->fetch(PDO::FETCH_ASSOC);
            return  true;
        }else return false ; 
    }
}