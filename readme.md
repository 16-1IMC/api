![Generic badge](https://img.shields.io/badge/Type-API-green.svg) ![Generic badge](https://img.shields.io/badge/Language-php_8.2-blue.svg) ![Generic badge](https://img.shields.io/badge/Framework-Symfony_5.4-purple.svg) ![Generic badge](https://img.shields.io/badge/Framework-Api_Platform_3.1-cyan.svg)

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
DATABASE_URL="mysql://<User>:<Password>@127.0.0.1:3306/<Nom de la Base>?serverVersion=8&charset=utf8mb4"
```

Si elle n'existe pas, créer une base de donnée

```bash
php bin/console doctrine:database:create
```

Mettre à jour le schéma de la base de donnée

```bash
php bin/console doctrine:schema:update --force
```

(Optionnel) Charger des données de test
Pour avoir les images chargées dans les fixtures, déplacer le contenu des 3 dossiers présent dans /public/fixtures dans /plublic/images.

```bash
php bin/console doctrine:fixture:load
```

Lancer le serveur

```bash
symfony serve
```

Une documentation de l'API est disponible à l'adresse http://localhost:8000/api

## Utilisation de l'API

Une fois sur la page de documentation, la totalité des routes seront visibles. En revanche pour pouvoir utiliser les routes nécessitant une authentification, il faudra se connecter avec un compte utilisateur.

Pour cela, envoyer une requête sur la route /api/auth avec la méthode POST et le body suivant :

```json
{
  "email": "example@email.com"
  "password": "password"
}
```

L'API renvera alors un token qu'il faudra ajouter en cliquant sur le bouton "Authorize" en haut à droite de la page de documentation. Il faudra alors ajouter le token dans le champ "Value" sous la forme suivante :

```
Bearer <token>
```

Une fois authentifié, la totalité des routes seront utilisables.

## Authors

- [@TheGoodDev](https://github.com/TheGooodDev)
- [@JLWear](https://github.com/JLWear)
- [@HPOIZAT](https://github.com/HPOIZAT)
