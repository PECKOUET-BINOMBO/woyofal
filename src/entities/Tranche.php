<?php
namespace App\Entities;

class Tranche {
    private int $id;
    private string $nom;
    private int $minKwh;
    private int $maxKwh;
    private float $prixUnitaire;

    public function __construct(int $id, string $nom, int $minKwh, int $maxKwh, float $prixUnitaire) {
        $this->id = $id;
        $this->nom = $nom;
        $this->minKwh = $minKwh;
        $this->maxKwh = $maxKwh;
        $this->prixUnitaire = $prixUnitaire;
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getMinKwh(): int { return $this->minKwh; }
    public function getMaxKwh(): int { return $this->maxKwh; }
    public function getPrixUnitaire(): float { return $this->prixUnitaire; }
}