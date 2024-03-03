<?php
// Les en-têtes CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers:*");

//On renvoie les authorisations CORS au navigateur qui emet les requetes 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // 204 pour car c'est une reponse sont contenu: No content 
    http_response_code(204);
    exit;
}
spl_autoload_register(function ($class) {
   $class =  str_replace("\\", DIRECTORY_SEPARATOR, $class);
   $class = __DIR__ . DIRECTORY_SEPARATOR ."src".DIRECTORY_SEPARATOR.$class.".php";
   require_once  $class;
});

use Model\JWT\JWT;
use Controller\ItemController;
use Controller\UserController;  
use Controller\MediaController;
use Controller\AddressController;
use Controller\CommentController;
use Controller\DonationController;

// http_response_code(204);

// //On vérifie si on reçoit un token
$uri = explode("/",$_SERVER['REQUEST_URI']);
$ressource  = $uri[1];

//Token d'autorisation est indispensable pour toute les requêtes sauf celle d'authentification et de recuperation

if ($ressource === 'signup' or $ressource === 'login' or $_SERVER['REQUEST_METHOD'] === "GET" or isset($_REQUEST)) {
}else{
    $token = "";
    if(isset($_SERVER['Authorization'])){
        $token = trim($_SERVER['Authorization']);
    }elseif(isset($_SERVER['HTTP_AUTHORIZATION'])){
        $token = trim($_SERVER['HTTP_AUTHORIZATION']);
    }elseif(function_exists('apache_request_headers')){
        $requestHeaders = apache_request_headers();
        if(isset($requestHeaders['Authorization'])){
            $token = trim($requestHeaders['Authorization']);
        }
    }
    // // On vérifie si la chaine commence par "Bearer" et si vide cela supposerait l'url origin a des déjà les authorisations
        if(!isset($token) || !preg_match('/Bearer\s(\S+)/', $token, $matches)){
            http_response_code(400);
            echo json_encode(['statut' => 2, 'message' => 'Token introuvable']);
            exit;
        }
        // // On extrait le token
        $token = str_replace('Bearer ', '', $token);
        $jwt = new JWT();
        // On vérifie la validité
        if(!$jwt->isValid($token)){
            http_response_code(400);
            echo json_encode(['statut' => 2,'message' => 'Token invalide']);
            exit;
        }

        // On vérifie la signature
        if(!$jwt->check($token, SECRET)){
        }

        // On vérifie l'expiration
        if($jwt->isExpired($token)){
            http_response_code(403);
            echo json_encode(['statut' => 2,'message' => 'Le token a expiré']);
            exit;
        }
}
// On dispatche les methodes aux controllers
switch ($ressource){
    case "signup":
    case "login":
    case "logout":
    case "updateUser":
    case "user":
        $controller =  new UserController();
        break;
    case "comment":
        $controller = new CommentController();
        break;
    case "recover":    
    case "item":
    case "items":
        $controller = new ItemController();
        break;
    case "donation":
        $controller = new DonationController();
        break;
    case "address":
        $controller = new AddressController();
        break;
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
// echo json_encode($jwt->getPayload($token));