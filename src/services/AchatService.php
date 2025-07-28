<?php
namespace App\Services;

use App\Entities\Achat;
use App\Repositories\{AchatRepository, CompteurRepository, TrancheRepository, JournalAchatRepository};
use App\Core\ResponseFormatter;
use App\Src\Enums\ErrorEnum;
use App\Src\Enums\SuccessEnum;
use App\Repositories\ClientRepository;


class AchatService {
    private AchatRepository $achatRepo;
    private CompteurRepository $compteurRepo;
    private TrancheRepository $trancheRepo;
    private JournalAchatRepository $journalAchatRepo;
    private ClientRepository $clientRepo;

    public function __construct(
        AchatRepository $achatRepo,
        CompteurRepository $compteurRepo,
        TrancheRepository $trancheRepo,
        JournalAchatRepository $journalAchatRepo,
        ClientRepository $clientRepo
    ) {
        $this->achatRepo = $achatRepo;
        $this->compteurRepo = $compteurRepo;
        $this->trancheRepo = $trancheRepo;
        $this->journalAchatRepo = $journalAchatRepo;
        $this->clientRepo = $clientRepo;
    }

    public function effectuerAchat(string $compteurNumero, float $montant, ?string $ip = null): array {
        $compteur = $this->compteurRepo->findByNumero($compteurNumero);
        if (!$compteur) {
            return ResponseFormatter::error(ErrorEnum::COMPTEUR_NOT_FOUND->value, 404);
        }

        $tranche = $this->trancheRepo->findTrancheByMontant($montant);
        if (!$tranche) {
            return ResponseFormatter::error(ErrorEnum::NO_TRANCHE_FOUND->value, 400);
        }

        $nbreKwh = $montant / $tranche->getPrixUnitaire();
        $reference = $this->generateReference();
        $codeRecharge = $this->generateCodeRecharge();
        $dateAchat = date('Y-m-d H:i:s');

        $achat = new Achat(
            $reference,
            $compteurNumero,
            $montant,
            $nbreKwh,
            $tranche->getId(),
            $codeRecharge
        );
        $this->achatRepo->save($achat);

        // Journalisation de l'achat
        if ($ip) {
            $this->journalAchatRepo->log($achat, $ip);
        }

        $clientNomPrenom = '';
        if ($compteur) {
            $client = $this->clientRepo->findById($compteur->getClientId());
            if ($client) {
                $clientNomPrenom = $client->nom . ' ' . $client->prenom;
            }
        }

        return ResponseFormatter::success([
            'compteur' => $compteurNumero,
            'reference' => $reference,
            'code' => $codeRecharge,
            'date' => $dateAchat,
            'tranche' => $tranche->getNom(),
            'prix' => $tranche->getPrixUnitaire(),
            'nbreKwt' => $nbreKwh,
            'client' => $clientNomPrenom
        ], "Achat effectué avec succès");
    }

    private function generateReference(): string {
        return 'WOYO-' . date('Ymd-His');
    }

    private function generateCodeRecharge(): string {
        return strtoupper(bin2hex(random_bytes(8)));
    }
}