<?php
namespace App\Repositories;

use App\Entities\Tranche;
use App\Services\DatabaseProvider;
use PDO;

class TrancheRepository {
    private PDO $pdo;

    public function __construct(DatabaseProvider $provider) {
        $this->pdo = $provider->getPdo();
    }

    public function findTrancheByMontant(float $montant): ?Tranche {
    $currentMonth = date('Y-m-01');
    $stmt = $this->pdo->prepare("
        SELECT * FROM tranches 
        WHERE :montant / prix_unitaire BETWEEN min_kwh AND max_kwh
        AND date_debut <= :currentMonth
        ORDER BY date_debut DESC
        LIMIT 1
    ");
    $stmt->execute([
        'montant' => $montant,
        'currentMonth' => $currentMonth
    ]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $data ? new Tranche($data['id'], $data['nom'], $data['min_kwh'], $data['max_kwh'], $data['prix_unitaire']) : null;
}
}