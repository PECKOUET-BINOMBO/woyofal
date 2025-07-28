<?php
namespace App\Repositories;

use App\Entities\Compteur;
use App\Services\DatabaseProvider;
use PDO;

class CompteurRepository {
    private PDO $pdo;

    public function __construct(DatabaseProvider $provider) {
        $this->pdo = $provider->getPdo();
    }

    public function findByNumero(string $numero): ?Compteur {
        $stmt = $this->pdo->prepare("SELECT * FROM compteurs WHERE numero = ?");
        $stmt->execute([$numero]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Compteur(
            $data['numero'],
            $data['client_id'],
            $data['adresse']
        ) : null;
    }
}