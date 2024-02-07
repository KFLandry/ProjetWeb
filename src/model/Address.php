<?
namespace Model;
use Model\DataBase\DataBaseConnexion;
use PDO;

class Address{
    private $id;
    private $road;
    private $name;
    private $postal;
    private $city;
    private $country;
    private $default;
}