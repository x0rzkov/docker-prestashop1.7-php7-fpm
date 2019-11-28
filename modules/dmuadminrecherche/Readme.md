# DMU Administration rapide des produits (par [Dream me up](http://www.dream-me-up.fr))

```
   .--.
   |   |.--..-. .--, .--.--.   .--.--. .-.   .  . .,-.
   |   ;|  (.-'(   | |  |  |   |  |  |(.-'   |  | |   )
   '--' '   `--'`-'`-'  '  `-  '  '  `-`--'  `--`-|`-'
        w w w . d r e a m - m e - u p . f r       '

  @author    Dream me up <prestashop@dream-me-up.fr>
  @copyright 2007 - 2015 Dream me up
  @license   All Rights Reserved

```

## changelog 4.1.3

* Modification du comportement des fancybox

## changelog 4.1.2

* Amélioration de la modification de catégorie par défaut

## changelog 4.1.1

* Ajout de l'action groupée : Suppression 

## changelog 4.1.0

* Compatibilité avec Prestashop 1.7
* Ajout d'un champs Prix Final qui inclus les réductions

## changelog 4.0.3

* Correction de l'affichage des images lorsque l'ancien système de stockage d'images est utilisé
* Correction pour le filtre par fournisseur
* Correction de l'affichage de l'en-tête du tableau lorsqu'il n'y a qu'un seul produit
* Correction de l'enregistrement du prix de vente HT des déclinaisons

## changelog 4.0.2

* Correction pour la modification d'un champ texte lorqu'il n'y a qu'une seule langue
* Correction pour afficher dans les formulaires la langue sélectionnée pour l'employé au lieu de la langue par défaut
* Affichage par défaut de la colonne "Prix de vente HT" si la colonne "Prix de vente TTC" n'est pas disponible
* Correction d'affichage des images lorsqu'elles ne sont pas carrées

## changelog 4.0.1

* Correction de l'affichage de la barre de recherche quand la gestion des stocks est désactivée
* Amélioration de l'affichage du filtre de recherche en responsive
* Correction de la position de l'administration rapide dans le menu catalogue à l'installation
* Correction d'une erreur lors de la mise à jour

## changelog 4.0.0

* Module redéveloppé de zéro pour améliorer l'ergonomie et la compatibilité avec Prestashop 1.6
* Nouveaux champs éditables en 1 clic : EAN-13, UPC, largeur du colis, hauteur du colis, profondeur du colis, fournisseur, prix de vente HT, quantité minimale, balise titre
* Ajout du tri par date de création
* Ajout d'un bouton pour dupliquer les produits
* Ajout de la gestion des stocks avancées pour les produits associés à un seul entrepôt
* Ajout de la recherche sur l'EAN-13 pour les déclinaisons
* Ajout possiblité de sélectionner plusieurs déclinaisons d'un produit à supprimer
* Ajout de l'affichage en responsive
* Le contenu des champs éditables est maintenant présélectionnés au clic
* Affichage de toutes les images associées aux déclinaisons
* Exécution des hooks lors des modifications
* Déplacement de la configuration de la pagination sous la liste des produits
* Regroupement des 2 listes de tri
* Correction avec les colonnes sélectionnées qui n'étaient pas toujours conservées quand on utilisait le filtre de recherche
* Correction de la traduction
* Correction de l'affichage de l'emplacement des produits dans les entrepôts
* Correction avec l'écotaxe
* Correction avec le bouton réinitialiser pour ne plus réinitialiser les colonnes sélectionnées et l'ordre des colonnes
* Correction du tri avec la pagination pour ne plus trier seulement les produits sur la page en cours mais tous les produits
* Optimisation de la taille des données retournées en ajax pour l'affichage des résultats

## changelog 3.0.1

* Correction de l'affichage de certaines images
* Correction de la traduction du nom du module
* Correction de l'affichage du module pour certaines versions de Prestashop 1.4
* Correction d'une erreur lors d'installation pour les langues autres que l'anglais et le français
* Correction de l'affichage des images des produits dans le listing
* Correction de l'affichage des références fournisseur dans le listing

## changelog 3.0.0

* Ajout de la compatibilité avec Prestashop 1.6
* Ajout colonne "Poids (colis)" éditable
* Possibilité de garder le filtre de recherche caché
* Correction des problèmes de changement de prix pour les produits avec écotaxe

## changelog 2.7.4

* Correction de l'affichage des prix si les taxes sont désactivées
* Correction de l'exécution des hooks lors la modification des quantités
* Correction de la modification des quantités en 1.4
* Correction de l'exécution des requêtes multiples

## changelog 2.7.3

* Correction pour les références fournisseurs des produits
* Correction pour le filtre de recherche des fournisseurs
* Correction de la modification des quantités lorsqu'elles sont à 0
* Ajout des références fournisseurs pour les déclinaisons
* Ajout de la recherche par ID produit
* Affichage du prix des déclinaisons en HT si l'affichage en HT est choisi dans la configuration du module
* Correction erreur addCSS()
* Correction suppression des déclinaisons en 1.4

## changelog 2.7.2

* Correction d'un bug avec les produits qui ne s'affichent pas lorsqu'ils n'ont pas d'image
* Correction de bug pour le multiboutique
* Correction d'un problème d'affichage pour les images des déclinaisons
* Ajout "Message quand en stock" éditable

## changelog 2.7.1

* Correction du bug avec les recherches qui ne se mettent pas à jour correctement
* Correction de bug avec certaines versions de Prestashop pour le multiboutique

## changelog 2.7

* Correction de problèmes avec le multiboutique si on travail avec plusieurs onglets et que l'on change de boutique
* Correction du bug de modification du prix d'achat
* Correction du bug avec la référence des déclinaisons qui ne s'affiche pas
* Correction du problème d'affichage des caractéristiques avec le multiboutique
* Correction du bug avec l'association des images aux déclinaisons des produit
* Correction du bug de modification de la déclinaison par défaut
* Correction du problème d'affichage de l'image du produit avec le multiboutique
* Correction du bug de modification des frais de port supplémentaires pour le multiboutique
* Correction du bug de modification de la catégorie par défaut
* Correction du bug lors de la suppression des déclinaisons pour le multiboutique
* Correction du bug avec le token
* Correction du bug de modification des stocks pour le multiboutique

## changelog 2.6.1

* Correction du bug de modification de la configuration du module
* Correction du problème d'affichage de l'infobulle dans la configuration du module
* Correction du bug avec l'affichage des prix en HT

## changelog 2.6

* Correction du bug du survol de l'image lors d'un scroll (chrome)
* Correction du bug de modification de prix en multiboutique
* Correction du bug de l'edition des ref et ref fournisseur en 1.4
* Correction du bug de l'edition des noms en multiboutique
* Correction du bug de l'activation en multiboutique
* Correction du bug de l'augmentation et de la diminution des prix sur l'action globale en 1.5
* Correction du bug sur le filtre par attributs et caracteristiques

## changelog 2.5

* Correction du prix TTC

## changelog 2.4

* Correction du bug du champs de recherche: pas de resultats, on n'affiche plus tout
* Message hors stock editable

## changelog 2.2

* Gestion des quantites en multiboutique
