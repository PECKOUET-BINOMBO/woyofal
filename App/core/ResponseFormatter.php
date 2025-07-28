<?php
namespace App\Core;
use App\Src\Enums\SuccessEnum;
use App\Src\Enums\ErrorEnum;

class ResponseFormatter {
   public static function json(array $response): void {
    if (!headers_sent()) {
        header('Content-Type: application/json');
        http_response_code($response['code'] ?? 200);
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit; // Important pour éviter tout output supplémentaire
}

    public static function success(array $data, ?string $message = null): array {
        return [
            'data' => $data,
            'statut' => 'success',
            'code' => 200,
            'message' => $message ?? 'Achat effectué avec succès',
        ];
    }

    public static function error(string $message, int $code = 400): array {
        return [
            'data' => null,
            'statut' => 'error',
            'code' => $code,
            'message' => $message
        ];
    }
}