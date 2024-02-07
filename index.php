<?php
use Model\JWT\JWT;
use Controller\ItemController;
use Controller\UserController;  
use Controller\MediaController;
use Controller\AddressController;
use Controller\CommentController;
include_once ("../ProjetWeb/ressources/config.php");
include_once ("../ProjetWeb/src/Model/JWT/JWT.php");
include_once ('../ProjetWeb/src/Controller/UserController.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


//On vérifie si on reçoit un token
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
// On vérifie si la chaine commence par "Bearer "
if(!isset($token) || !preg_match('/Bearer\s(\S+)/', $token, $matches)){
    http_response_code(400);
    echo json_encode(['mess
    age' => 'Token introuvable']);
    exit;
}
// On extrait le token
$token = str_replace('Bearer ', '', $token);

$jwt = new JWT();
// On vérifie la validité
if(!$jwt->isValid($token)){
    http_response_code(400);
    echo json_encode(['message' => 'Token invalide']);
    exit;
}


// On vérifie la signature
if(!$jwt->check($token, SECRET)){
    http_response_code(403);
    echo json_encode(['message' => 'Le token est invalide']);
    exit;
}

// On vérifie l'expiration
// if($jwt->isExpired($token)){
//     http_response_code(403);
//     echo json_encode(['message' => 'Le token a expiré']);
//     exit;
// }
// On dispatche les methodes aux controllers
$uri =  explode("?",$_SERVER['REQUEST_URI']);
$string=explode("=",$uri[1]);
$ressources = $string[1];
$controller;
switch ($ressources){
    case "signup":
    case "login":
    case "logout":
    case "user":
        $controller = new UserController();
        break;
    case "comment":
        $controller = new CommentController();
        break;
    case "item":
        $controller = new ItemController();
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
// Je performe la methode handleRequest de l'interface Controller
$controller->handleRequest();
// echo json_encode($jwt->getPayload($token));