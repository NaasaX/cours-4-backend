# Guide d'installation et d'utilisation

## Installation rapide

### 1. Prérequis

- Docker et Docker Compose
- Ou PHP 8.4+ avec Composer

### 2. Installation des dépendances

```bash
docker compose run --rm php composer install
```

### 3. Configuration de la base de données

```bash
# Créer la base de données
docker compose run --rm php php bin/console doctrine:database:create

# Exécuter les migrations
docker compose run --rm php php bin/console doctrine:migrations:migrate --no-interaction
```

### 4. Générer des données de test

```bash
docker compose run --rm php php bin/console app:populate-database

# Avec options personnalisées
docker compose run --rm php php bin/console app:populate-database --buildings=10 --people-per-building=5
```

### 5. Démarrer l'application

```bash
docker compose up
```

L'application est maintenant accessible sur **http://localhost:8080**

## Utilisation

### Pages disponibles

1. **Page d'accueil** : http://localhost:8080/
   - Liste tous les bâtiments
   - Affiche le nombre de personnes par bâtiment

2. **Détail d'un bâtiment** : http://localhost:8080/building/1
   - Informations du bâtiment
   - Tableau des personnes qui y travaillent

3. **Liste des personnes** : http://localhost:8080/people
   - Tableau de toutes les personnes
   - Lien vers leur bâtiment

### Commandes console

```bash
# Afficher l'aide de la commande de peuplement
docker compose run --rm php php bin/console app:populate-database --help

# Générer 3 bâtiments avec 20 personnes chacun
docker compose run --rm php php bin/console app:populate-database --buildings=3 --people-per-building=20

# Lister toutes les commandes disponibles
docker compose run --rm php php bin/console list
```

## Tests

### Exécuter tous les tests d'un coup

```bash
./run-tests.sh
```

### Exécuter les tests individuellement

```bash
# Style de code
docker compose run --rm php composer run phpcs

# Corriger automatiquement le style
docker compose run --rm php composer run phpcs:fix

# Analyse statique
docker compose run --rm php composer run phpstan

# Tests unitaires
docker compose run --rm php php bin/phpunit

# Tests unitaires avec détails
docker compose run --rm php php bin/phpunit --testdox
```

## Installation des Git Hooks

Pour activer les vérifications automatiques :

```bash
# Pre-commit (vérifie le style avant chaque commit)
ln -s ../../githooks/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

# Pre-push (exécute tous les tests avant chaque push)
ln -s ../../githooks/pre-push .git/hooks/pre-push
chmod +x .git/hooks/pre-push
```

Pour désactiver temporairement :

```bash
git commit --no-verify -m "message"
git push --no-verify
```

## Dépannage

### Le serveur ne démarre pas

```bash
# Vérifier les logs
docker compose logs

# Redémarrer les containers
docker compose down
docker compose up
```

### Erreur de base de données

```bash
# Supprimer et recréer la base
docker compose run --rm php php bin/console doctrine:database:drop --force
docker compose run --rm php php bin/console doctrine:database:create
docker compose run --rm php php bin/console doctrine:migrations:migrate --no-interaction
```

### Les tests échouent

```bash
# Vérifier la configuration PHPUnit
cat phpunit.xml.dist

# Vider le cache
docker compose run --rm php php bin/console cache:clear

# Réinstaller les dépendances
docker compose run --rm php composer install
```

### Permission refusée sur les scripts

```bash
chmod +x run-tests.sh
chmod +x githooks/pre-commit
chmod +x githooks/pre-push
```

## Développement

### Ajouter une nouvelle entité

```bash
docker compose run --rm php php bin/console make:entity
```

### Créer une migration

```bash
docker compose run --rm php php bin/console make:migration
docker compose run --rm php php bin/console doctrine:migrations:migrate
```

### Créer un nouveau controller

```bash
docker compose run --rm php php bin/console make:controller
```

### Créer une nouvelle commande

```bash
docker compose run --rm php php bin/console make:command
```

## Sans Docker

Si vous n'utilisez pas Docker, remplacez `docker compose run --rm php` par rien :

```bash
# Au lieu de :
docker compose run --rm php php bin/console app:populate-database

# Utilisez :
php bin/console app:populate-database

# Au lieu de :
docker compose run --rm php composer install

# Utilisez :
composer install
```

Démarrage du serveur sans Docker :

```bash
php -S 0.0.0.0:8080 -t public
```

-
