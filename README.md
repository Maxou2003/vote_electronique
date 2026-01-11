# Système de vote électronique

### Présentation

Ce projet est une implémentation pédagogique d’un système de vote électronique à bulletin secret en PHP, suivant une architecture MVC simple.
Il met en œuvre :

    Un serveur PHP “classique” (Apache + PHP)

    Une architecture MVC : Controllers / Models / Views

    Des mécanismes cryptographiques basés sur RSA et GMP :

        signatures aveugles (blind signatures) pour l’anonymat

        hachage des bulletins

        chiffrement des bulletins pour le décompteur

    Un stockage des codes de vote et des bulletins dans des fichiers JSON.

Le but est de reproduire le schéma du TD “Vote électronique” (année 2025–2026) : électeur, administrateur, anonymiseur, commissaire, décompteur, codes N1N1 / N2N2, signatures aveugles, etc.

​
### Prérequis

    PHP ≥ 8.2 avec l’extension GMP activée (obligatoire pour les opérations RSA).

​

Apache (stack LAMP “native”) avec DocumentRoot configuré sur /var/www/html.

PHP configuré pour lire un fichier .env (chargé via une fonction maison load_env()).

Afin de pouvoir faire fonctionner ce projet assurer vous d'avoir un fichier .env, celui-ci devra être au format du .env-example.

Pour générer les clés rsa vous pouvez passer par le fichier "generate_rsa.php".

Afin que le projet fonctionne correctement la taille de la clé RSA du décompteur doit être significativement plus grande que celle de l'admin (par exemple : rsa admin 64bits, rsa décompteur 2048 bits).

Cela évite que les données du bulletin chiffré par l'utilisateur soient trop longue (du fait de la signature de l'administrateur).
