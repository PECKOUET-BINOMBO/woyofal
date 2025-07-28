<?php
namespace App\Services;

use App\Entities\Achat;
use App\Repositories\{AchatRepository, CompteurRepository, TrancheRepository};
use App\Core\ResponseFormatter;
use App\Src\Enums\ErrorEnum;
use App\Src\Enums\SuccessEnum;

class AchatService {
    private AchatRepository $achatRepo;
    private CompteurRepository $compteurRepo;
    private TrancheRepository $trancheRepo;

    public function __construct(
        AchatRepository $achatRepo,
        CompteurRepository $compteurRepo,
        TrancheRepository $trancheRepo
    ) {
        $this->achatRepo = $achatRepo;
        $this->compteurRepo = $compteurRepo;
        $this->trancheRepo = $trancheRepo;
    }

    public function effectuerAchat(string $compteurNumero, float $montant): array {
        if (!$this->compteurRepo->findByNumero($compteurNumero)) {
            return ResponseFormatter::error(ErrorEnum::COMPTEUR_NOT_FOUND->value, 404);
        }

        $tranche = $this->trancheRepo->findTrancheByMontant($montant);
        if (!$tranche) {
            return ResponseFormatter::error(ErrorEnum::NO_TRANCHE_FOUND->value, 400);
        }
        $nbreKwh = $montant / $tranche->getPrixUnitaire();

        $achat = new Achat(
            $this->generateReference(),
            $compteurNumero,
            $montant,
            $nbreKwh,
            $tranche->getId(),
            $this->generateCodeRecharge()
        );
        $this->achatRepo->save($achat);

        return ResponseFormatter::success([
            'reference' => $achat->getReference(),
            'code' => $achat->getCodeRecharge(),
            'nbreKwh' => $nbreKwh,
            'tranche' => $tranche->getNom()
        ], SuccessEnum::SUCCESS->value);
    }

    private function generateReference(): string {
        return 'WOYO-' . date('Ymd-His');
    }

    private function generateCodeRecharge(): string {
        return strtoupper(bin2hex(random_bytes(8)));
    }
}