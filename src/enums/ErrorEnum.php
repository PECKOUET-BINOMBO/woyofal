<?php

namespace App\Src\Enums;

enum ErrorEnum: string
{
    case ECHEC_CONNEXION = 'Connexion au serveur PostgreSQL échouée :';
    case ECHEC_CREATE_DATABASE = 'Erreur lors de la création de la base :';
    case ECHEC_CONNEXION_BASE = 'Erreur de connexion à la base de données : ';
    case ECHEC_SERVER = 'Erreur de connexion au serveur : ';
    case ECHEC_CREATION_TABLE = 'Erreur lors de la création de la table :';
    case ECHEC_INSERTION = 'Erreur lors de l\'insertion des données :';
    case DATABASE_INEXISTANTE = 'La base de données n\'existe pas. Veuillez d\'abord exécuter les migrations.';
    case ERROR_SEEDING = 'Erreur lors du seeding.';
    case TABLE_INEXISTANTE = 'La table n\'existe pas. Veuillez d\'abord exécuter les migrations.';
    case ERROR_UPLOAD_IMAGE = 'Erreur lors du téléchargement de l\'image :';
    case NCI_NOT_FOUND = 'NCI non trouvé.';
    case ERROR = 'erreur :';
    case ECHEC_SEED = 'Erreur lors du seeding :';
    case COMPTEUR_NOT_FOUND = 'Compteur non trouvé';
    case NO_TRANCHE_FOUND = 'Aucune tranche trouvée pour ce montant';
}