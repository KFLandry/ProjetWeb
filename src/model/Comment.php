<?
namespace Model;
use Model\DataBase\DataBaseConnexion;
use PDO;
class Comment{
    private $id;
    private $idUser;
    private $idItems;
    private $rate;
    private $message;
    private $like;
    private $reports;
}