# Système de vote électronique

### Présentation

Ce projet est une implémentation pédagogique d’un système de vote électronique à bulletin secret en PHP, suivant une architecture MVC simple.

Il a été réalisé pour répondre à un sujet de TD basé sur le sujet [suivant]( https://www.di.ens.fr/~nitulesc/files/CRYPTO13/TD5.pdf).

### Prérequis

Afin de pouvoir faire fonctionner ce projet assurer vous d'avoir un fichier .env, celui-ci devra être au format du .env-example.

Pour générer les clés rsa vous pouvez passer par le fichier "generate_rsa.php".

Afin que le projet fonctionne correctement la taille de la clé RSA du décompteur doit être significativement plus grande que celle de l'admin (par exemple : rsa admin 64bits, rsa décompteur 2048 bits).

Cela évite que les données du bulletin chiffré par l'utilisateur soient trop longue (du fait de la signature de l'administrateur).

### Mise en place 

#### Docker
Avant de commencer cette partie assurer vous d'avoir fini la partie [`Prérequis`](#prérequis).

Ce projet est dockerisé, il est donc hautement conseillé de le lancer via docker.

Il vous suffit pour cela de lancer la commande suivante :

```bash
docker compose up --build
```

#### Autre 

Si jamais vous voulez lancer le projet sans passer par le conteneur docker, il vous faudra :

    - PHP 8.2 ou supérieur
    - L'extension GMP de PHP
    - un serveur apache avec la permission d'éditer les fichiers: n1.json, n2.json, bulletins.json