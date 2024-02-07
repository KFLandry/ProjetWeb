<?
namespace Model;
use Model\DataBase\DataBaseConnexion;
use PDO;

class User{
    private $id;
    private $lastName;
    private $firstName;
    private $email;
    private  $role;
    private  $phone;
    private $password;
    private $birthday;
    private $con;
    private array $result;

    public function __construct(){
        $con = DataBaseConnexion::getUniqueInstance();
        $this->con =  $con->getConnexion();
        var_dump($this->con);
    }
    public function getUser(int $id){
        $sql ="SELECT * 
               FROM ed_user 
               JOIN ed_media 
               ON ed_user.id =  ed_media.idUser 
               WHERE id=$this->id
               ";
        $stmt= $this->con->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->fillUser($row);
        $this->result =  $row;
    }
    private function fillUser($row){
        $this->id  =  $row["id"];
        $this->lastName  =  $row["lastName"];
        $this->firstName  =  $row["firstName"];
        $this->birthday  =  $row["birthday"];
        $this->email  =  $row["email"];
        $this->phone  =  $row["phone"];
        $this->password  =  $row["password"];
    }
    public function getUsers(){
        $sql ="SELECT * 
        FROM ed_user 
        JOIN ed_media 
        ON ed_user.id =  ed_media.idUser 
        WHERE id=$this->id
        ";
        $stmt= $this->con->query($sql);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->result =  $row;
    }
    public function login($credentials) : bool{
        $email  =  $credentials["email"];
        $password = $credentials["password"];
        $sql = "SELECT * FROM ed_user WHERE email=$email";
        $row= $this->con->query($sql)->fetch(PDO::FETCH_ASSOC);
        if ($row){
            if (password_verify($password,$row["password"])){
                $this->fillUser($row);
                return true;
            }
        }
        return false;
    }
    public function update(){

    }
    public function logout(){
    }
    public function signup($data) :  bool{
    	$sql =  "INSERT INTO ed_user (firstName,lastName,email,phone,birthday,role,password) VALUES (?,?,?,?,?,?,?,)";
        $stmt =  $this->con->prepare($sql);
    	$stmt->bindParam("fistName",$data["firstName"],PDO::PARAM_STR);
        $stmt->bindParam("lastName",$data["lastName"],PDO::PARAM_STR);
        $stmt->bindParam("email",$data["email"],PDO::PARAM_STR);
        $stmt->bindParam("phone",$data["phone"],PDO::PARAM_INT);
        $stmt->bindParam("birthday",$data["birthday"],PDO::PARAM_STR);
        $stmt->bindParam("role",$data["role"],PDO::PARAM_STR);
        $password =  $data["password"].SALTING_KEY;
        $password =  password_hash($password,PASSWORD_DEFAULT);
        $stmt->bindParam("password",$data["password"],PDO::PARAM_STR); 
        return $stmt->execute();
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function getResult() : array  {
        return $this->result;
    }
    public function __destruct(){}
}