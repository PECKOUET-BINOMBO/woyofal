<?php
namespace App\Core;

use PDO;
use PDOException;
use App\Src\Enums\ErrorEnum;
use Dotenv\Dotenv;

class Database extends Singleton
{
    private ?PDO $connection = null;
    private ?PDO $serverConnection = null;
    private string $dbName;

    protected function __construct()
    {
        // Ajoute ce bloc pour charger .env si ce n'est pas déjà fait
        if (empty($_ENV['DB_NAME'])) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }
        $this->dbName = $_ENV['DB_NAME'] ?? '';
    }

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $dsn = $_ENV['DSN'] ?? '';
            $user = $_ENV['DB_USER'] ?? '';
            $pass = $_ENV['DB_PASS'] ?? '';
            try {
                $this->connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (PDOException $e) {
                throw new \Exception(ErrorEnum::ECHEC_CONNEXION_BASE->value . $e->getMessage());
            }
        }
        return $this->connection;
    }

    public function getServerConnection(): PDO
    {
        if ($this->serverConnection === null) {
            $driver = strtolower($_ENV['DB_DRIVER'] ?? 'pgsql');
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $port = $_ENV['DB_PORT'] ?? '5432';
            $user = $_ENV['DB_USER'] ?? '';
            $pass = $_ENV['DB_PASS'] ?? '';
            if ($driver === 'pgsql') {
                $dsn = "pgsql:host=$host;port=$port;dbname=postgres";
            } else {
                $dsn = "mysql:host=$host;port=$port";
            }
            try {
                $this->serverConnection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (PDOException $e) {
                throw new \Exception(ErrorEnum::ECHEC_SERVER->value . $e->getMessage());
            }
        }
        return $this->serverConnection;
    }

    public function setDatabaseName(string $dbName): void
    {
        $this->dbName = $dbName;
        // Réinitialiser la connexion pour prendre en compte la nouvelle base
        $this->connection = null;
    }
}