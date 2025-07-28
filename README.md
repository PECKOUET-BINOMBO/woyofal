# AppWoyofal

AppWoyofal est une application PHP qui simule le système de prépaiement d’électricité de la Senelec (Woyofal).  
Elle permet la gestion des clients, compteurs, achats de crédit, tranches tarifaires et la génération de codes de recharge.

---

## Fonctionnalités

- Gestion des clients et compteurs
- Achat de crédit d’électricité (Woyofal)
- Génération de codes de recharge
- Gestion des tranches tarifaires (tarification par kWh)
- Historique des achats
- API RESTful pour les opérations principales
- Système de migration et de seed de la base de données
- Gestion centralisée des messages d’erreur et de succès via Enums

---

## Installation

1. **Cloner le projet**  
   ```bash
   git clone <votre-url-git>
   cd AppWoyofal
   ```

2. **Installer les dépendances**  
   ```bash
   composer install
   ```

3. **Configurer l’environnement**  
   Copier le fichier `.env.example` en `.env` et renseigner vos paramètres de connexion à la base de données.

   ```bash
   cp .env.example .env
   ```

4. **Générer l’autoload**  
   ```bash
   composer dump-autoload
   ```

---

## Structure du projet

```
App/
  config/         # Fichiers de configuration (services, ...)
  core/           # Classes de base (Database, ResponseFormatter, ...)
  migrations/     # (optionnel) Anciennes migrations
database/
  migrations/     # Scripts de migration (création des tables)
  seeders/        # Scripts de seed (insertion de données initiales)
public/           # Point d’entrée (index.php)
route/            # Définition des routes
src/
  controllers/    # Contrôleurs (AchatController, CompteurController, ...)
  entities/       # Entités métiers (Achat, Compteur, Tranche, ...)
  enums/          # Enums pour messages, statuts, etc.
  repositories/   # Accès aux données (AchatRepository, ...)
  services/       # Logique métier (AchatService, ...)
templates/        # (optionnel) Vues HTML
vendor/           # Dépendances Composer
```

---

## Démarrage rapide

1. **Créer la base de données et les tables**  
   ```bash
   composer database:migrate
   ```

2. **Insérer les données initiales (seed)**  
   ```bash
   composer database:seed
   ```

3. **Lancer le serveur de développement**  
   ```bash
   composer serve
   # ou
   php -S localhost:8000 -t public
   ```

4. **Accéder à l’application**  
   Ouvrez [http://localhost:8000](http://localhost:8000) dans votre navigateur.

---

## Commandes utiles

- `composer database:migrate` : Crée la base de données et les tables
- `composer database:seed`    : Insère les données de base (clients, compteurs, tranches, etc.)
- `composer serve`            : Lance le serveur PHP local

---

## Auteur

- Paul Emile (<apeckouet@gmail.com>)

---

##