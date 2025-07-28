<?php
namespace App\Controllers;

use App\Services\AchatService;
use App\Core\ResponseFormatter;

class AchatController {
    private AchatService $achatService;

    public function __construct(AchatService $achatService) {
        $this->achatService = $achatService;
    }

    public function acheter(array $requestData): void {
        $response = $this->achatService->effectuerAchat(
            $requestData['compteur'],
            $requestData['montant']
        );
        ResponseFormatter::json($response);
    }
}