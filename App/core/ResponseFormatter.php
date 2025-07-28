<?php
namespace App\Core;
use App\Src\Enums\SuccessEnum;
use App\Src\Enums\ErrorEnum;

class ResponseFormatter {
    public static function json(array $response): void {
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public static function success(array $data, ?string $message = null): array {
        return [
            'data' => $data,
            'status' => SuccessEnum::SUCCESS->value,
            'code' => 200,
            'message' => $message ?? SuccessEnum::OPERATION_SUCCESS->value,
        ];
    }

    public static function error(string $message, int $code = 400): array {
        return [
            'data' => null,
            'status' => ErrorEnum::ERROR->value,
            'code' => $code,
            'message' => $message
        ];
    }
}