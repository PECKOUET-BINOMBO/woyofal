<?php

use App\Controllers\AchatController;
use App\Controllers\CompteurController;

return [
    // Vérifier l'existence d'un numéro de compteur
    '/api/compteur/{numero}' => [
        'controller' => CompteurController::class,
        'method' => 'verifierCompteur',
        'methods' => ['GET'],
    ],

    // Générer un achat (référence, code de recharge, nombre de kWh, date, tranche, prix kWh)
    '/api/achat' => [
        'controller' => AchatController::class,
        'method' => 'acheter',
        'methods' => ['POST'],
    ],
];