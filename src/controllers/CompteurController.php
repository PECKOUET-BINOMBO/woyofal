<?php
namespace App\Controllers;

use App\Repositories\CompteurRepository;
use App\Core\ResponseFormatter;
use App\Src\Enums\ErrorEnum;


class CompteurController {
    private CompteurRepository $compteurRepo;

    public function __construct(CompteurRepository $compteurRepo) {
        $this->compteurRepo = $compteurRepo;
    }

    public function verifierCompteur(string $numero): void {
        $compteur = $this->compteurRepo->findByNumero($numero);
        ResponseFormatter::json(
            $compteur 
                ? ResponseFormatter::success(['exists' => true])
                : ResponseFormatter::error(ErrorEnum::COMPTEUR_NOT_FOUND->value, 404)
        );
        
    }
}