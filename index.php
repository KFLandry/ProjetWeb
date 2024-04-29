<?php
require_once realpath(__DIR__ . '/vendor/autoload.php');
use Dotenv\Dotenv; 

/////// en mode test
/////// if (getenv('APP_ENV') !== 'production'){
$dotenv =  Dotenv::createImmutable(__DIR__);
$dotenv->load();
/////// }
// Les en-têtes CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers:*");

//On renvoie les authorisations CORS au navigateur qui emet les requetes 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

use Controller\ItemController;
use Controller\UserController;  
use Controller\MediaController;
use Controller\ResidenceController;
use Controller\CommentController;
use Controller\DonationController;

$ressource ="";
if (isset($_SERVER[$_ENV["PATHINFO"]])){
    $uri = explode("/",$_SERVER[$_ENV["PATHINFO"]]);
    $ressource  = $uri[1];
}
// On dispatche les methodes aux controllers
switch ($ressource){
    case "" : 
        http_response_code(200);
        echo "Bienvenue sur l'api REST d'educycle";
        exit;
    case "signup":
    case "login":
    case "logout":
    case "accountVerification":
    case "user":
        $controller =  new UserController();
        break;
    case "comment":
        $controller = new CommentController();
        break;
    case "recover":
    case 'files':
    case "item":
    case "items":
        $controller = new ItemController();
        break;
    case "donation":
        $controller = new DonationController();
        break;
    case "residence":
        $controller = new ResidenceController();
        break;
    case "mediaUpdate":
    case "media" :
        $controller = new MediaController();
        break;
    default : 
        http_response_code(404);
        echo json_encode("message  :  Ressource not found");
        exit;
}
// Je performe la methode handleRequest de la classe AbstractController
$controller->handleRequest();