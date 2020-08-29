# API de notation d'élèves

## Objectif

Créer une API de notation d'élèves en Symfony.

Un élève est caractérisé par :
* Un nom
* Un prénom
* Une date de naissance

Une note est caractérisée par :
* Une valeur : entre 0 et 20
* Une matière : champ texte

L'API devra permettre de :
* Ajouter un élève
* Modifier les informations d'un élève (nom, prénom, date de naissance)
* Supprimer un élève
* Ajouter une note à un élève
* Récupérer la moyenne de toutes les notes d'un élève
* Récupérer la moyenne générale de la classe (moyenne de toutes les notes données)

## Solution

La solution mise en œuvre est une API REST ouverte (sans authentification) :

* Versioning : [Git](https://git-scm.com/)
* Infra : [Docker](https://www.docker.com/) + [Docker Compose](https://docs.docker.com/compose/)
* Serveur web : [Caddy](https://caddyserver.com/)
* Base de données : [PostgreSQL](https://www.postgresql.org/) + [Adminer](https://www.adminer.org/)
* Application : [PHP](https://www.php.net/) + [Symfony](https://symfony.com/) + [API Platform](https://api-platform.com/)

### Installation

Docker, Docker Compose et Git sont requis sur la machine hôte.

Éditer le fichier `/etc/hosts` de la machine hôte pour ajouter :

```
127.0.0.1 ubi.loc
127.0.0.1 adminer.loc
```

Clôner le projet :

```
git clone git@github.com:vrobic/ubi.git /install/path
```

où `/install/path` est le répertoire d'installation du projet.

Se placer dans ce répertoire (`cd /install/path`) puis exécuter les commandes suivantes :

1. `cp .env.dist .env`
2. `docker-compose up -d`

Installer ensuite le projet Symfony :

```
docker-compose exec php composer install
docker-compose exec php bin/console doctrine:database:create
docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec php bin/console hautelook:fixtures:load --no-interaction
```

L'API est désormais accessible depuis [http://ubi.loc](http://ubi.loc), et Adminer depuis [http://adminer.loc](http://adminer.loc).

### Tests

Les quelques tests réalisés sont uniquement fonctionnels et ont été mis en place à des fins de démonstration. Ils ne prétendent pas être exhaustifs.

Ils peuvent être exécutés comme suit :

```
docker-compose exec php bin/phpunit
```

### Utilisation de l'API

Les exemples présentés permettent de répondre aux objectifs listés dans l'énoncé.

Pour accéder à la documentation exhaustive de l'API, consulter [http://ubi.loc](http://ubi.loc).

#### Ajouter un élève

`POST http://ubi.loc/students`

Corps de la requête :

```
{
  "firstName": "Vincent",
  "lastName": "Robic",
  "birthDate": "1990-06-24"
}
```

#### Modifier les informations d'un élève (nom, prénom, date de naissance)

`PATCH http://ubi.loc/students/{id}`

où `{id}` est l'identifiant de l'élève.

Corps de la requête :

```
{
  "birthDate": "1990-06-25"
}
```

#### Supprimer un élève

`DELETE http://ubi.loc/students/{id}`

où `{id}` est l'identifiant de l'élève.

####  Ajouter une note à un élève

`POST http://ubi.loc/grades`

Corps de la requête :

```
{
  "value": 20,
  "student": "/students/14",
  "subject": "API Platform"
}
```

où `/students/14` est l'IRI de l'élève.

#### Récupérer la moyenne de toutes les notes d'un élève

`GET http://ubi.loc/students/{id}/average-grade`

où `{id}` est l'identifiant de l'élève.

#### Récupérer la moyenne générale de la classe (moyenne de toutes les notes données)

`GET http://ubi.loc/students/average-grade`
