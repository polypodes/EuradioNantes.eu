# Cahier des spécification Euradionantes
*v1 - 7 juillet 2015*

## Sur toutes les pages

### Administration

Il existe deux niveau d'administration sur le site

- les **rédacteur** accèdent au contenus, peuvent en créer et non le publier
- Les **administrateurs** gèrent l'ensemble des fonctionnalités

Les contenus peuvent être créés, publiés, dé-publiés, supprimés facilement.

L'URL d'une page est créée dés son enregistrement, elle peut-être modifiée (hors numérotation et identifiant google news).
Les URLs doivent être écrites en cohérence avec les normes Google News

Mettre en place un outil d'allègement des images (pagespeed d'Apache ou autre via Cron).

Les Méta description peuvent être administrées dans les pages

Mettre en place lors de la migration les principes de re-directions d'URL

### Menu principal

**Retour accueil**

**La radio**, pages de contenu en arborescence n2, il est possible d'ajouter de nouvelles pages à ce niveau d'arborescence et de l'ordonner

- Le projet Euradionantes
- La radio-école
- Nous rejoindre
- Les partenaires 
- Qui sommes-nous

**L’actu**, filtre par mots clés créant la navigation, il est possible d'en ajouter

- Politique
- Économie
- Société
- Culture
- Évènements
- Revue de presse
- Inclassables

**Les émissions**, grille au format PDF, et deux formats d’affichage : programme de la semaine, émission par ordre alphabétique

- Toute la grille, lien vers un PDF
- Le programme de la semaine, grille horaire des émission jour par jour, avec une navigation sur la semaine (structure actuelle du site à faire évoluer)
- Toutes les émission par ordre alphabétique, pagination si nécessaire, suivante / précédente et nb de page
	
Les émissions et programmes sont classés par catégories :

- Musical
- Infos
- Débats

**La musique**, morceaux et albums classés par grille de diffusion, playlist, label du mois, album de la semaine. Des postcast remontés en session live et interview

- C’est quoi ce titre, liste des morceaux diffusés par date et heure, morceau en cours d’écoute
- La playlist, choix de morceaux dans la liste des albums, petit descriptif expliquant les choix, classé par date, pagination
- les interview, des podcasts filtrés par date et nom
- les sessions lives, des podcasts filtrés par date et nom
- Le label du mois, un regroupement de morceaux et album, chapeauté d’un descriptif, classé par date
- L’album de la semaine, un album sélectionné dans la liste, chapeauté d’un descriptif, classé par date 

**Contact**

- Formulaire
- Map

**Menu secondaire**

- Contact
- Plan du site
- Crédits
- Mentions légales

**Le lecteur « en direct » **

- lien vers le pop-up de lecture
- « c’est quoi ce titre ? » 

**Recherche**

La page de résultats affiche les réponses par rubriques
Utiliser Elastic Search ou Google Search


**Liens réseaux sociaux**

- Twitter
- Facebook
- Youtube
- Flickr

**Inscription à la newsletter**

Utilise l'API Mailchimp, à relier à un compte existant

- votre email
- valider

## Page d’accueil

Cette page est composée de contenus éditoriaux, des actualités et des podcasts formatés comme des actualités.
Les actualités sont ordonnées manuellement.
Les formats des actualités, entre autre les images, doivent être compatible avec la publication sur Facebook (Opengraph)

plusieurs niveaux de mise en forme d’actualités :

**Actualité principale** grande image

- image
- date
- catégorie
- titre court
- résumé

Deux ou trois (a définir avec le volume disponible en créa) **actualités secondaires**

- image
- date
- catégorie
- titre court
- résumé

**Des actualités courtes**, 4-5

- date
- catégorie
- titre court
- résumé

**Des brèves** 6-7

- date
- catégorie
- titre court

**Les podcasts** peuvent être remontés comme des **actualités** et présent sous une des formes au dessus sur la page d'accueil

- image
- date
- type
- titre court
- résumé

## La radio

Gabarits de pages d’articles géré en arborescence sur un seul niveau n2

- titre de page
- titre cours menu
- contenu RTE
- URL

## L’actu

Les actualités peuvent être associées à une ou plusieurs catégorie (case à cocher).
Il est possible d'associer des actualités ou des podcasts manuellement à une actualités pour une lecture complémentaire

### Page liste

actualités par ordre chronologique 

- titre cours menu
- date
- catégorie
- image
- résumé RTE

- filtre par catégorie
	- Politique
	- Économie
	- Société
	- Culture
	- Évènements
	- Revue de presse
	- Inclassables

### Page détail

- titre de page
- titre cours menu (non affiché)
- date
- catégorie
- résumé RTE
- contenu RTE
- image
- URL
- commentaires
	- nom
	- email
	- commentaires (pas de limite du nombre de caractères)
	- captcha
	- soumettre
- Actualités liés
	- titre court
	- catégorie
	- résumé
	- image
- partager
	- twitter
	- Google +
	- facebook
	- j’ailme facebook

## Les émission

### Toute la grille

- titre
- texte RTE
- lien Pdf à télécharger, type de fichier, poids

### Liste programme de la semaine

La gestion de la grille est à calquer sur l'ancienne version et à améliorer si possible. Déterminer comment gérer la récursivité.


- Affichage par créneaux horaires 
	- titre court
	- heure de début
	- heure de fin
	- image 
- navigation par jours
	- numéro de semaine, suivante / précédente
	- jour de la semaine en lien
	- découpage en matinée, après midi, soirée, nuit
- trie par catégorie (mise en évidence dans la page des ressources triées)
	- musicale
	- Info
	- Débat 

### Les émissions de A à Z

Emissions classées par ordre chronologique

- titre court
- image
- résumé 
- filtre par catégorie, menu déroulant des émission après chaque catégorie
	- musicale
	- Info
	- Débat 
- Filtre par langue

### Émissions

- titre de page
- titre cours menu (sur liste)
- langue (s , plusieurs possibles)
- heure de début
- heure de fin
- jour et fréquence de diffusion ??? à caler avec la grille
- fréquence (mensuelle, etc… périodicité)
- Type 
	- musicale
	- Info
	- Débat
- résumé RTE
- contenu RTE
- image
- URL
- partager
	- twitter
	- Google +
	- facebook
	- j’ailme facebook
- podcast associés par ordre de diffusion
	- Titre
	- image
	- date
	- heure
	- résumé
	- lecteur audio
- pagination suivant / précédent, nb de page
 
### Podcast

Le podcast peut être en ligne dés que publié mais la lecture audio n'est possible que lorsque la date de diffusion est révolue.
Il faut trouver une solution permettant de chapitrer les podcasts. Mettre un marqueur dans la lecture qui affiche une information au survol, permettant d'accéder facilement à un moment clé (commentaire Ronan : il faut du contenu VTT cf W3C). Ce titre de ce marqueur est affiché lorsqu'on l'atteint dans la lecture.

Back office : gérer la création des podcasts sur une seule interface (ajout son, date de diffusion, informations et émission associée, publication)

- titre de page
- titre cours menu
- émission associée
- date de diffusion
- heure de diffusion
- résumé RTE
- contenu RTE
- image
- lecteur audio
- URL
- marqueur
	- titre
	- valeur temporelle
- télécharger le podcast
	- Type de fichier
	- Poids
	- lien de téléchargement direct du MP3
- exporter le podcast
	- affiche les instructions d’insertion
	- affiche le code HTML à insérer dans la page
- commentaires
	- nom
	- email
	- commentaires
	- captcha
	- soumettre
- partager
	- twitter
	- Google +
	- facebook
	- j’ailme facebook
- podcast associés même émission, par ordre chronologique 
	- Titre
	- image
	- date
	- heure
	- résumé
- lien retour vers l’émission


## La musique

Les albums, titres et interprètes, sont récupérés via le flux fourni par l’application de programmation hébergée chez Euradionantes. 
Le site interroge le serveur régulièrement (temps à définir).
Lorsque le morceau est passé à l'antenne, il est rapatrié, comparé avec la base existante.
Si pas encore référencé une fonctionnalité doit récupérer sur la base d’Amazon les information complémentaires, album, photo, et les autres titres.

### album
	
- nom de l'album
- interprète
- visuel
- label 
- titres de l'album
- pays
- année de sortie

### titres
	
- titre
- infos de l'album

### "C’est quoi ce titre ?" = écoute en live

Les dates et heure de diffusions sont récupérées du serveur de programmation d’Euradionantes.
Le lecteur en direct est présent dans cette pop-up., on peut donc écouter la radio.
Si le direct est une émission, l’outil affiche les information de l’émission.
La page affiche le morceau en cours écoute, celui à venir, et celui juste diffusé.
Un lien permet de voir tous les morceaux diffusé dans la journée par créneaux horaires.

#### l'écoute en direct 

Différents flux d'écoute sont disponibles
La demande est : 

- plusieurs formats MP3, OGG, AAC > déterminer si nécessaire pour les différents lecteurs HTML, et comment est fournis le fichier par Euradio
- plusieurs qualités 96 Kbs, 180 Kbs, etc > déterminer plus précisément ces débits, comment ils sont fournis, comment le serveur supporte la charge de consultation

#### Emissions

Il peut y avoir deux émissions qui se superposent, car une émission (un podcast entier) peut contenir des sous émission (des podcast unitaires). Il y aura donc deux lignes pour les émissions. Si une émission est en cours, et qu'une autre se place dans le même créneau horaire, mais avec une heure de début et de fin inférieure, elle s'affiche sur une nouvelle ligne.

- titre court
- catégorie
- image
- résumé 
- heure de début
- heure de fin
- lien vers l'émission

X2

#### Session musical, titres

Les titres sont chapeauté "session musicale"

- Nom de l'album
- visuel de l'album	
- interprètes
- pays
- année de sortie

#### Navigation

Il est possible de naviguer sur une semaine de programmation. ??? a valider

- navigation par heure
- navigation par jour
- affichage de l'évènement en cours
- affichage de l'évènement précédent

### la playlist

L’administrateur recherche dans la liste des albums importés et les ajoute à la playlist qu’il a créé. 
L’interface d'administration permet une recherche de albums par nom.

**Page liste** affiche les playlist par date

- titre (reprend la semaine de diffusion)
- date de création
- résumé
- pagination si nécessaire, suivante / précédente
- nb de page

**Page de détail** liste des albums sélectionnés dans la liste des albums récupérés

- titre 
- date de création
- résumé
- association d’album (ordre administrable)
	- Artiste
	- titre de l’album
	- label
	- pays
	- image
- navigation playlist suivante / précédente

### Les interviews

Ce sont des podcasts centrés sur des interviews

**Page liste** affiche les interviews par date

- titre 
- date de diffusion
- heure de diffusion
- résumé
- image
- lecteur audio
- pagination si nécessaire, suivante / précédente
- nb de page

**Page de détail**

- titre de page
- titre cours menu
- date de diffusion
- heure de diffusion	
- résumé RTE
- contenu RTE
- image
- lecteur audio
- URL
- télécharger le podcast
	- Type de fichier
	- Poids
	- lien de téléchargement direct du MP3
- exporter le podcast
	- affiche les instructions d’insertion
	- affiche le code HTML à insérer dans la page
- commentaires
	- nom
	- email
	- commentaires
	- captcha
	- soumettre
- partager
	- twitter
	- Google +
	- facebook
	- j’aime facebook


### Les sessions lives

Ce sont des podcasts centrés sur des les session lives

*Idem interviews*

### Le label du mois

Les labels réunissent des albums préalablement importés

**Page liste** affiche les labels par mois

- titre 
- résumé
- image
- pagination suivante / précédente
- nb de page

**Page détail** 

- titre 
- descriptif RTE 
- image
- albums associés
	- Nom de l'album
	- interprètes
	- visuel	
	- pays
	- année de sortie
- pagination suivant / précédent

### L’album de la semaine

Les albums du mois sont choisis dans la liste importée

**Page liste** affiche les albums par semaines

- titre de la sélection
- résumé
- visuel de l'album
- pagination suivante / précédente
- nb de page

**Page détail** 

- titre de la sélection
- descriptif RTE 
- Nom de l'album
- visuel de l'album	
- interprètes
- pays
- année de sortie
- pagination  suivant / précédent


## Contact

### Formulaire de contact

- choix du destinataire
	- Direction
	- Rédaction
	- Webmaster et communication
	- Technique
	- Programamtion
	- Partenariats
	- Comptabilité
- Nom *
- Société 
- email *
- sujet
- message *
- Captcha
- envoyer

### Map

Google Map 

- positionnement d’Euradio
- lien itinéraire
- agrandir

## Plan site

Sitemap auto

## Crédits, Mentions légales

Contenus gabarits standarts

- titre de page
- titre cours menu
- contenu RTE
- URL
