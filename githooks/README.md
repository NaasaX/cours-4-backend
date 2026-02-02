# Git Hooks

Ce dossier contient les hooks Git pour automatiser les vérifications de qualité du code.

## Installation

Pour installer les hooks, exécutez les commandes suivantes depuis la racine du projet :

```bash
# Pre-commit hook (vérifie le style de code)
ln -s ../../githooks/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

# Pre-push hook (exécute tous les tests)
ln -s ../../githooks/pre-push .git/hooks/pre-push
chmod +x .git/hooks/pre-push
```

## Hooks disponibles

### pre-commit

- Vérifie le style de code avec PHPCS
- Bloque le commit si des erreurs sont trouvées
- Suggère d'utiliser `phpcs:fix` pour corriger automatiquement

### pre-push

- Exécute PHPCS (style de code)
- Exécute PHPStan (analyse statique)
- Exécute PHPUnit (tests unitaires)
- Bloque le push si des tests échouent

## Désactivation temporaire

Pour désactiver temporairement les hooks, utilisez l'option `--no-verify` :

```bash
git commit --no-verify -m "message"
git push --no-verify
```
