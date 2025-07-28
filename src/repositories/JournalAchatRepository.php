<?php 

namespace App\Repositories;

use App\Entities\Achat;
use App\Services\DatabaseProvider;
use PDO;

class JournalAchatRepository {
    private PDO $pdo;

    public function __construct(DatabaseProvider $provider) {
        $this->pdo = $provider->getPdo();
    }

    public function log(Achat $achat, string $ip): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO journal_achats (achat_id, ip, statut, date_operation)
            VALUES (
                (SELECT id FROM achats WHERE reference = ? LIMIT 1),
                ?, 'success', NOW()
            )
        ");
        $stmt->execute([
            $achat->getReference(),
            $ip
        ]);
    }
}