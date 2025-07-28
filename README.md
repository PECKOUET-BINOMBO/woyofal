# Mini-Framework-php

Un mini framework PHP pour créer rapidement des applications web modernes.

## Fonctionnalités

- Architecture MVC simple
- Routing flexible (fichiers `route/web.php` et `route/api.php`)
- Injection de dépendances via configuration YAML
- Gestion de session et validation
- Système de middlewares
- Autoload PSR-4 compatible Composer

## Installation

```bash
composer create-project mr-sem-s/mini-framework-php
```

## Structure du projet

```
App/
  config/
  core/
  ...
database/
public/
route/
src/
templates/
vendor/
```

## Démarrage rapide

1. Cloner le projet ou installer via Composer.
2. Configurer vos routes dans `route/web.php` ou `route/api.php`.
3. Ajouter vos contrôleurs dans `src/controllers/`.
4. Lancer le serveur PHP :

```bash
php -S localhost:8000 -t public
```

## Auteur

- Pape Sembene (<sembenpape4@gmail.com>)

## Licence

MIT