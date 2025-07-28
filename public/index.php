<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Initialisation de la base de données
$database = \App\Core\Database::getInstance();

// Chargement du routeur
require_once __DIR__ . '/../route/api.php';

// Traitement de la requête
$router = new \App\Core\Router(require __DIR__ . '/../route/api.php');
$router->dispatch();