<?php
namespace App\Database\Migration;

require_once __DIR__ . '/../../vendor/autoload.php';

use PDO;
use App\Core\Database;
use App\Src\Enums\ErrorEnum;
use App\Src\Enums\SuccessEnum;
use Dotenv\Dotenv;

class Migration
{
    private string $dbName;
    private Database $database;
    private string $driver;

    public function __construct()
    {
        // Charger les variables d'environnement
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->database = Database::getInstance();
        $this->dbName = $_ENV['DB_NAME'] ?? '';
        $this->driver = strtolower($_ENV['DB_DRIVER'] ?? 'pgsql');
    }

    public function run(): void
    {
        echo "--- Migration AppWoyofal ---\n\n";
        $this->checkOrCreateDatabase();
        $this->migrateTables();
    }

    private function checkOrCreateDatabase(): void
    {
        $pdo = $this->database->getServerConnection();
        try {
            if ($this->driver === 'pgsql') {
                $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '{$this->dbName}'");
                $exists = $stmt->fetch();
            } else {
                $stmt = $pdo->query("SHOW DATABASES LIKE '{$this->dbName}'");
                $exists = $stmt->fetch();
            }

            if (!$exists) {
                echo "Base de données '{$this->dbName}' introuvable.\n";
                $response = readline("Créer la base ? (O/N) : ");
                if (strtoupper(trim($response)) === 'O') {
                    $pdo->exec("CREATE DATABASE \"{$this->dbName}\"");
                    echo SuccessEnum::SUCCESS_CREATE_DATABASE->value . " '{$this->dbName}'.\n";
                } else {
                    exit("Migration annulée.\n");
                }
            }
        } catch (\PDOException $e) {
            exit(ErrorEnum::ECHEC_CREATE_DATABASE->value . $e->getMessage() . "\n");
        }
        $this->database->setDatabaseName($this->dbName);
    }

    private function migrateTables(): void
    {
        $pdo = $this->database->getConnection();
        try {
            $this->createClientsTable($pdo);
            $this->createCompteursTable($pdo);
            $this->createTranchesTable($pdo);
            $this->createAchatsTable($pdo);
            $this->seedInitialData($pdo);
            echo SuccessEnum::MIGRATION_SUCCESS->value . "\n";
        } catch (\PDOException $e) {
            exit(ErrorEnum::ECHEC_CREATION_TABLE->value . $e->getMessage() . "\n");
        }
    }

    private function createClientsTable(PDO $pdo): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS clients (
                id SERIAL PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                telephone VARCHAR(20) UNIQUE NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";
        $pdo->exec($sql);
        echo "Table 'clients' créée.\n";
    }

    private function createCompteursTable(PDO $pdo): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS compteurs (
                numero VARCHAR(50) PRIMARY KEY,
                client_id INTEGER NOT NULL REFERENCES clients(id) ON DELETE CASCADE,
                adresse TEXT,
                est_actif BOOLEAN DEFAULT TRUE
            );
        ";
        $pdo->exec($sql);
        echo "Table 'compteurs' créée.\n";
    }

    private function createTranchesTable(PDO $pdo): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS tranches (
                id SERIAL PRIMARY KEY,
                nom VARCHAR(50) NOT NULL,
                min_kwh INTEGER NOT NULL,
                max_kwh INTEGER NOT NULL,
                prix_unitaire DECIMAL(10, 2) NOT NULL,
                date_debut DATE NOT NULL DEFAULT CURRENT_DATE
            );
        ";
        $pdo->exec($sql);
        echo "Table 'tranches' créée.\n";
    }

    private function createAchatsTable(PDO $pdo): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS achats (
                id SERIAL PRIMARY KEY,
                reference VARCHAR(100) UNIQUE NOT NULL,
                compteur_numero VARCHAR(50) NOT NULL REFERENCES compteurs(numero),
                montant DECIMAL(10, 2) NOT NULL,
                nbre_kwh DECIMAL(10, 2) NOT NULL,
                tranche_id INTEGER NOT NULL REFERENCES tranches(id),
                code_recharge VARCHAR(50) UNIQUE NOT NULL,
                date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                ip_client VARCHAR(45),
                statut VARCHAR(20) DEFAULT 'success' CHECK (statut IN ('success', 'error'))
            );
        ";
        $pdo->exec($sql);
        echo "Table 'achats' créée.\n";
    }

    private function seedInitialData(PDO $pdo): void
    {
        // Vérifie si les tranches existent déjà
        $stmt = $pdo->query("SELECT COUNT(*) FROM tranches");
        if ($stmt->fetchColumn() == 0) {
            $sql = "
                INSERT INTO tranches (nom, min_kwh, max_kwh, prix_unitaire) VALUES
                ('tranche1', 0, 100, 50.00),
                ('tranche2', 101, 200, 75.00);
            ";
            $pdo->exec($sql);
            echo "Données initiales (tranches) insérées.\n";
        }
    }
}

// Exécution
(new Migration())->run();