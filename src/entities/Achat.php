<?php
namespace App\Entities;

class Achat {
    private string $reference;
    private string $compteurNumero;
    private float $montant;
    private float $nbreKwh;
    private int $trancheId;
    private string $codeRecharge;

    public function __construct(
        string $reference,
        string $compteurNumero,
        float $montant,
        float $nbreKwh,
        int $trancheId,
        string $codeRecharge
    ) {
        $this->reference = $reference;
        $this->compteurNumero = $compteurNumero;
        $this->montant = $montant;
        $this->nbreKwh = $nbreKwh;
        $this->trancheId = $trancheId;
        $this->codeRecharge = $codeRecharge;
    }

    public function getReference(): string {
        return $this->reference;
    }

    public function getCompteurNumero(): string {
        return $this->compteurNumero;
    }

    public function getMontant(): float {
        return $this->montant;
    }

    public function getNbreKwh(): float {
        return $this->nbreKwh;
    }

    public function getTrancheId(): int {
        return $this->trancheId;
    }

    public function getCodeRecharge(): string {
        return $this->codeRecharge;
    }
}