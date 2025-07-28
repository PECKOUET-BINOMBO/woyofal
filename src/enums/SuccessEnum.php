<?php

namespace App\Src\Enums;

enum SuccessEnum: string
{
    case MIGRATION_SUCCESS = 'Les migrations ont été exécutées avec succès dans la base';
    case SUCCESS_CREATE_DATABASE = 'Base de données créée avec succès';
    case SUCCESS_CONNECTION = 'Connexion réussie à la base de données';
    case SUCCESS_INSERTION = 'Données insérées avec succès.';
    case SEARCH_SUCCESS = 'Recherche réussie.';
    case SUCCESS = 'succès';
    case SEED_SUCCESS = 'Seeding terminé avec succès.';
    case CLIENTS_INSERTED = '%d clients insérés.';
    case COMPTEURS_INSERTED = '%d compteurs insérés.';
    case TRANCHES_INSERTED = '%d tranches tarifaires insérées.';
    case TRANCHES_EXIST = 'Les tranches tarifaires existent déjà.';
    case ACHATS_INSERTED = '%d achats insérés.';
    case TRANCHES_INITIAL = 'Données initiales (tranches) insérées.';
    case TABLE_CREATED = "Table '%s' créée.";
    case OPERATION_SUCCESS = 'Opération réussie.';
}