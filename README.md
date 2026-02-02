# Gestion des Bâtiments et Personnes

Application Symfony 7 pour gérer des bâtiments et les personnes qui y travaillent.

La documentation de [Symfony, c'est ici !](https://symfony.com/doc/current/index.html)

## Fonctionnalités

- **Entités** : Building (bâtiment) et Person (personne) avec relation OneToMany
- **Pages web** : Liste des bâtiments, détail d'un bâtiment avec ses occupants, liste des personnes
- **Commande console** : Génération de données factices avec FakerPHP
- **Tests** : Tests unitaires avec PHPUnit
- **Qualité de code** : PHPCS et PHPStan configurés
- **Git hooks** : Pre-commit et pre-push pour automatiser les vérifications
- **CI/CD** : GitHub Actions configuré

## Note

Pour ce projets, je vous laisse le choix de le faire marcher avec docker ou non.  
Certain on des problème de performance/lenteur avec docker, vous pourrez utiliser votre composer/php local en gardant bien en tête que ce n'est pas une bonne pratique.

Sur la configuration docker ; vous verez une ligne "user" pour le service php. Elle sert a préciser quel user écrira sur la machine hote, par defaut l'identifiant du user et du groupe est 1000.  
Vous trouverez votre valeur avec la commande suivante, et changer si cela est necessaire.

```bash
echo "UID: ${UID}"
```

Symofony se base égalment sur sa commande `bin/console` pour faire tourner le serveur de développement.

ce fichier `console` est normalement un executable. Si vous êtes sous windows, il se peut que vous ayez des problème d’execution. Dans ce cas, préciser de l'executer avec `php`:

```bash
docker compose exec php php bin/console make
```

## Prérequis

Il faut respecter une de ces deux conditions:

- `Docker` avec `docker compose`
- `php8.4` avec les extension php`CType`, `iconv`, `session`, `simpleXML` et `Tokenizer`. Et bien sur `composer`

## Installation

Il suffi d'installer les depandance via composer

```bash
docker compose run --rm php composer install
```

ou sans docker

```bash
composer install
```

## Démarrage du projet

### 1. Installer les dépendances

```bash
docker compose run --rm php composer install
```

### 2. Créer la base de données et exécuter les migrations

```bash
docker compose run --rm php php bin/console doctrine:database:create
docker compose run --rm php php bin/console doctrine:migrations:migrate --no-interaction
```

### 3. Peupler la base de données avec des données de test

```bash
docker compose run --rm php php bin/console app:populate-database
# Options disponibles:
# --buildings=5 (nombre de bâtiments, défaut: 5)
# --people-per-building=10 (nombre de personnes par bâtiment, défaut: 10)
```

### 4. Démarrer le serveur

```bash
docker compose up
```

L'application est accessible sur [http://localhost:8080](http://localhost:8080)

Sans docker:

```bash
php -S 0.0.0.0:8080 -t public
```

## Lancement des tests

### Tests individuels

```bash
# Style de code (PHPCS)
docker compose run --rm php composer run phpcs
docker compose run --rm php composer run phpcs:fix

# Analyse statique (PHPStan)
docker compose run --rm php composer run phpstan

# Tests unitaires (PHPUnit)
docker compose run --rm php php bin/phpunit
```

### Tous les tests en une seule commande

```bash
./run-tests.sh
```

## Git Hooks

Des hooks Git sont disponibles pour automatiser les vérifications :

### Installation des hooks

```bash
ln -s ../../githooks/pre-commit .git/hooks/pre-commit
ln -s ../../githooks/pre-push .git/hooks/pre-push
chmod +x .git/hooks/pre-commit .git/hooks/pre-push
```

- **pre-commit** : Vérifie le style de code avant chaque commit
- **pre-push** : Exécute tous les tests avant chaque push

## Structure du projet

```
src/
├── Command/
│   └── PopulateDatabaseCommand.php  # Commande pour générer des données
├── Controller/
│   └── BuildingController.php       # Contrôleur pour les pages web
├── Entity/
│   ├── Building.php                 # Entité Bâtiment
│   └── Person.php                   # Entité Personne
└── Repository/
    ├── BuildingRepository.php
    └── PersonRepository.php

templates/
└── building/
    ├── index.html.twig              # Liste des bâtiments
    ├── show.html.twig               # Détail d'un bâtiment
    └── people.html.twig             # Liste des personnes

tests/
└── Entity/
    ├── BuildingTest.php             # Tests unitaires Building
    └── PersonTest.php               # Tests unitaires Person

migrations/
└── Version*.php                      # Migrations de base de données

githooks/
├── pre-commit                        # Hook pre-commit
├── pre-push                          # Hook pre-push
└── README.md

.github/
└── workflows/
    └── ci.yml                        # GitHub Actions CI/CD
```

## Routes disponibles

- `/` ou `/buildings` - Liste de tous les bâtiments
- `/building/{id}` - Détails d'un bâtiment avec ses occupants
- `/people` - Liste de toutes les personnes
