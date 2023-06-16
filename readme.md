[![Generic badge](https://img.shields.io/badge/Type-API-green.svg)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/Language-php_8.2-blue.svg)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/Language-symfony_5.4-purple.svg)](https://shields.io/)

# StyleStock API

API du projet StyleStock dans le cadre du projet de fin d'année de notre Bachelor 3 Informatique à Ynov Campus Lyon.

## Lancement en local

Cloner le projet

```bash
  git clone https://github.com/16-1IMC/api.git
```

Se rendre dans le répertoire
    
```bash
cd api
```

Installer les dépendances
    
```bash
composer install
```

Configurer la base de donnée le fichier .env.local

```bash
    DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8&charset=utf8mb4"
```

Mettre à jour le schéma de la base de donnée

```bash
  php bin/console doctrine:schema:update --force
```

(Optionnel) Charger des données de test

```bash
  php bin/console doctrine:fixture:load
```

Lancer le serveur

```bash
  symfony serve
```

Une documentation de l'API est disponible à l'adresse http://localhost:8000/api

## Authors

- [@TheGoodDev](https://github.com/TheGooodDev)
- [@JLWear](https://github.com/JLWear)
- [@HPOIZAT](https://github.com/HPOIZAT)