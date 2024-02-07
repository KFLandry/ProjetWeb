<?php
namespace Model\DataBase ;

class DataBaseConnexion{
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
        $this->con = new \PDO($this->dns,$this->userName,$this->password);
        $this->con->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    }catch(\PDOException $e){
        throw new \Exception($e);
    }
   }
   public  static function getUniqueInstance() : DataBaseConnexion{
    if (!self::$uniqueInstance){
      self::$uniqueInstance = new DataBaseConnexion(); 
    }
    return self::$uniqueInstance;
   } 
   public function getConnexion() : \PDO{
    return $this->con;
}
}