<?php
namespace App\Database\Seeders;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Src\Enums\ErrorEnum;
use App\Src\Enums\SuccessEnum;
use PDO;

class Seeder
{
    private PDO $pdo;
    private Database $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function run(): bool
    {
        if (!$this->checkDatabaseExists()) {
            echo ErrorEnum::ECHEC_CONNEXION->value . ErrorEnum::DATABASE_INEXISTANTE->value . "\n";
            return false;
        }

        try {
            $this->pdo = $this->database->getConnection();
            $this->pdo->beginTransaction();

            $this->seedClients();
            $this->seedCompteurs();
            $this->seedTranches();
            $this->seedAchats();

            $this->pdo->commit();
            echo SuccessEnum::SEED_SUCCESS->value . "\n";
            return true;

        } catch (\PDOException $e) {
            if (isset($this->pdo)) {
                $this->pdo->rollBack();
            }
            echo ErrorEnum::ECHEC_SEED->value . $e->getMessage() . "\n";
            return false;
        }
    }

    private function checkDatabaseExists(): bool
    {
        try {
            $this->pdo = $this->database->getConnection();
            $tables = ['clients', 'compteurs', 'tranches', 'achats'];
            foreach ($tables as $table) {
                $result = $this->pdo->query("SELECT to_regclass('public.$table')");
                if ($result->fetchColumn() === null) {
                    echo ErrorEnum::TABLE_INEXISTANTE->value . " ($table)\n";
                    return false;
                }
            }
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    private function seedClients(): void
    {
        $clients = [
            [
                'nom' => 'Diop',
                'prenom' => 'Amina',
                'telephone' => '+221781234567'
            ],
            [
                'nom' => 'Ndiaye',
                'prenom' => 'Moussa',
                'telephone' => '+221761234567'
            ],
            [
                'nom' => 'Fall',
                'prenom' => 'Fatou',
                'telephone' => '+221771234567'
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO clients (nom, prenom, telephone)
            VALUES (:nom, :prenom, :telephone)
        ");

        foreach ($clients as $client) {
            $stmt->execute($client);
        }
        echo count($clients) . " clients insérés.\n";
    }

    private function seedCompteurs(): void
    {
        $compteurs = [
            ['numero' => 'SNE-789012', 'client_id' => 1, 'adresse' => 'Dakar, Sacré-Cœur'],
            ['numero' => 'SNE-345678', 'client_id' => 2, 'adresse' => 'Thiès, Château'],
            ['numero' => 'SNE-901234', 'client_id' => 3, 'adresse' => 'Saint-Louis, Hydrobase']
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO compteurs (numero, client_id, adresse)
            VALUES (:numero, :client_id, :adresse)
        ");

        foreach ($compteurs as $compteur) {
            $stmt->execute($compteur);
        }
        echo count($compteurs) . " compteurs insérés.\n";
    }

    private function seedTranches(): void
    {
        // Vérifie si les tranches existent déjà
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM tranches");
        if ($stmt->fetchColumn() == 0) {
            $tranches = [
                ['nom' => 'tranche1', 'min_kwh' => 0, 'max_kwh' => 100, 'prix_unitaire' => 50.00],
                ['nom' => 'tranche2', 'min_kwh' => 101, 'max_kwh' => 200, 'prix_unitaire' => 75.00],
                ['nom' => 'tranche3', 'min_kwh' => 201, 'max_kwh' => 300, 'prix_unitaire' => 100.00]
            ];

            $stmt = $this->pdo->prepare("
                INSERT INTO tranches (nom, min_kwh, max_kwh, prix_unitaire)
                VALUES (:nom, :min_kwh, :max_kwh, :prix_unitaire)
            ");

            foreach ($tranches as $tranche) {
                $stmt->execute($tranche);
            }
            echo count($tranches) . " tranches tarifaires insérées.\n";
        } else {
            echo "Les tranches tarifaires existent déjà.\n";
        }
    }

    private function seedAchats(): void
    {
        $achats = [
            [
                'reference' => 'WOYO-20250101-001',
                'compteur_numero' => 'SNE-789012',
                'montant' => 5000.00,
                'nbre_kwh' => 100.00,
                'tranche_id' => 1,
                'code_recharge' => 'ABCD-EFGH-IJKL',
                'ip_client' => '192.168.1.1'
            ],
            [
                'reference' => 'WOYO-20250102-001',
                'compteur_numero' => 'SNE-345678',
                'montant' => 7500.00,
                'nbre_kwh' => 100.00,
                'tranche_id' => 2,
                'code_recharge' => 'MNOP-QRST-UVWX',
                'ip_client' => '192.168.1.2'
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO achats (reference, compteur_numero, montant, nbre_kwh, tranche_id, code_recharge, ip_client)
            VALUES (:reference, :compteur_numero, :montant, :nbre_kwh, :tranche_id, :code_recharge, :ip_client)
        ");

        foreach ($achats as $achat) {
            $stmt->execute($achat);
        }
        echo count($achats) . " achats insérés.\n";
    }
}

// Point d'entrée
(new Seeder())->run();