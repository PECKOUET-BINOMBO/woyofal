<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\DataBase;

$database = DataBase::getInstance();
$pdo = $database->getConnection();
