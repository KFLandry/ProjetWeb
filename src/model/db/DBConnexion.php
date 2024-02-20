<?php
namespace Model\DB ;
use PDO;
use PDOException;
require_once "../ProjetWeb/ressources/config.php";

class DBConnexion{
   private  $dns;
   private  $userName;
   private $dbName;
   private  $password;
   private $con;
   private static $uniqueInstance;
   private function __construct(){
    $this->dns  =  DB_HOST;
    $this->dbName = DB_NAME;
    $this->userName =  DB_USERNAME;
    $this->password  =  DB_PASSWORD;
    try{
        $this->con = new PDO("mysql:host=$this->dns;dbname=$this->dbName",$this->userName,$this->password);
        $this->con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo json_encode(['statut' => 2,'message'=> $e->getMessage()]);
        exit;
    }
   }
   public  static function getUniqueInstance() : DBConnexion{
    if (!self::$uniqueInstance){
      self::$uniqueInstance = new DBConnexion(); 
    }
    return self::$uniqueInstance;
   } 
   public function getConnexion() : PDO{
    return $this->con;
}
}