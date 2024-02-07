<?php

use Model\JWT\JWT;
require_once("../ProjetWeb/src/model/JWT/JWT.php");
require_once("../ProjetWeb/ressources/config.php");
// On crée le header
$header = [
    'typ' => 'JWT',
    'alg' => 'HS256'
];

// On crée le contenu (payload)
$payload = [
    'user_id' => 123,
    'roles' => [
        'ROLE_ADMIN',
        'ROLE_USER'
    ],
    'email' => 'contact@demo.fr'
];

$jwt  = new JWT();
echo $jwt->generate($payload, $header,SECRET);
