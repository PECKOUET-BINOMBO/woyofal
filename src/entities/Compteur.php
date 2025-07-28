<?php
namespace App\Entities;

class Compteur {
    private string $numero;
    private int $clientId;
    private ?string $adresse;

    public function __construct(string $numero, int $clientId, ?string $adresse = null) {
        $this->numero = $numero;
        $this->clientId = $clientId;
        $this->adresse = $adresse;
    }

    // Getters uniquement (immutable)
    public function getNumero(): string {
        return $this->numero;
    }

    public function getClientId(): int {
        return $this->clientId;
    }

    public function getAdresse(): ?string {
        return $this->adresse;
    }
}