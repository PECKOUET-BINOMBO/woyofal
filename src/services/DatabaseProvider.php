<?php
namespace App\Services;

use App\Core\DataBase;
use PDO;

class DatabaseProvider {
    public function getPdo(): PDO {
        return DataBase::getInstance()->getConnection();
    }
}