<?php
namespace App\Repositories;

use App\Entities\Achat;
use App\Services\DatabaseProvider;
use PDO;

class AchatRepository {
    private PDO $pdo;

    public function __construct(DatabaseProvider $provider) {
        $this->pdo = $provider->getPdo();
    }

    public function save(Achat $achat): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO achats (reference, compteur_numero, montant, nbre_kwh, tranche_id, code_recharge)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $achat->getReference(),
            $achat->getCompteurNumero(),
            $achat->getMontant(),
            $achat->getNbreKwh(),
            $achat->getTrancheId(),
            $achat->getCodeRecharge()
        ]);
    }
}