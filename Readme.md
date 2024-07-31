## Contexte

Ce script PHP est conçu pour traiter le flux de produits de AdoreMe, disponible ici : [https://gw-services.prd.adoreme.com/v2/feeds/veesual](https://gw-services.prd.adoreme.com/v2/feeds/veesual). Il télécharge le flux JSON, le traite, et génère un fichier JSON contenant les options disponibles pour chaque produit.

## Objectif

L'objectif est de créer un format standardisé pour chaque produit avec les options disponibles. Il faut sauvegarder le fichier JSON de sortie sur le disque dur.

## Exécuter le script

Pour exécuter le script depuis le terminal, utilisez la commande suivante :

```bash
php index.php
```

### Difficultés rencontrées

Dans le cadre de ce test technique, j'ai rencontré particulièrement une difficulté sur le regroupement des produits "regular" et "plus".

J'ai utilisé le nom du produit pour retirer " Plus" et regrouper les versions "regular" et "plus", mais je pense que cette méthode n'est pas fiable à 100%. Je n'ai malheureusement pas pu trouvé mieux.
