<?php
namespace App\Controllers;

use App\Services\AchatService;
use App\Core\ResponseFormatter;

class AchatController {
    private AchatService $achatService;

    public function __construct(AchatService $achatService) {
        $this->achatService = $achatService;
    }

   public function acheter(): void {
    // Récupération des données JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['compteur'], $data['montant'])) {
        ResponseFormatter::json(ResponseFormatter::error('Données manquantes', 400));
        return;
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $response = $this->achatService->effectuerAchat(
        $data['compteur'],
        (float)$data['montant'],
        $ip
    );

    ResponseFormatter::json($response);
}
}