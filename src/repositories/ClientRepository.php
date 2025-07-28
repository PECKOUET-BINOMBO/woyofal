<?php
namespace App\Repositories;

use App\Services\DatabaseProvider;
use PDO;

class ClientRepository {
    private PDO $pdo;

    public function __construct(DatabaseProvider $provider) {
        $this->pdo = $provider->getPdo();
    }

    public function findById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? (object)$data : null;
    }
}