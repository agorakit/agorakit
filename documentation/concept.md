# Mobilizator
A tool for those who want to (self) mobilize

The goal of this tool is to allow citizens and activists to communicate, decide, organize and rejoice.

The mean is to remove the need for any admin or moderator in the process. The goal is to increase the resilience of groups.

Here follows the concept, in french.

##Principes de base
* Pas de “single point of failure” humain
* Pas d’administrateur unique par groupe
* Les membres sont cooptés par le groupe
* La modération est faite par le groupe
* Toute action importante est cogérée par le groupe
* Centré autour du groupe et pas l’individu
* Utilise la machine pour faciliter le travail humain (notamment dans les calculs, clôtures, auto modération, etc...). Chaque groupe a sa vitesse de fonctionnement, sa taille, la machine s’adapte.
* Extrêmement simple à utiliser
* Prise de position forte des développeurs (pas de feature creep)
* Open source
* Auto hébergeable facilement (lamp) sur un compte partagé.
* Ouvert à l’extérieur (api, import d’une boite mail par pop, publication automatique de certains contenus par rss, ical, passerelle vers les réseaux sociaux)

##Inspiration
* Meetup : génial mais propriétaire et payant
* Phabricator : génial mais trop orienté développeurs et trop compliqué à utiliser
* Loomio : très bien pour les discussions et la prise de décision, mais manque certaines fonctionnalités. Difficile à héberger (pas lamp)
* Slashdot : pour la modération des commentaires
* Github : pour le wiki simple
* Les mailing listes : pour la difficulté de gestion et l’avalanche de mails :-)
* Basecamp : en faisant les premiers mockups de l’interface, je me suis rendu compte que ça ressemble hyper fort.

##Structure
* Page d’accueil
* Décrit le principe
* Liste les groupes
* Recherche de groupes, y compris par zone géographique, mot clé, etc.
* Montre à l’utilisateur les groupes proches de chez lui (par code postal par ex.)
* Propose d’en rejoindre (= meetup)
* Créer un nouveau groupe

Groupe ou plutôt cercle (comme chez podemos).

Ensuite, pour chaque cercle, sous forme d’onglets (ou autre système de menu) :


##Onglet discussions
(En gros comme Loomio, avec quelques améliorations)
* Liste des derniers fils de discussion + nombre de réponses non lues pour chaque fil. Quand on clique, on arrive directement à l’endroit des non lus 
* Historique des modifications d’un fil et des réponses
* Possibilité de +1 -1 (anonyme) sur chaque réponse ainsi que “J’apprécie” qui est nominatif
* Quand une réponse est trop downvotée (algorithme à définir), il est replié (comme sur slashdot). Threshold automatique.
* Fils de discussions classés par “thématique”. Par défaut propose au moins “Général” et “Contact de l’extérieur”. Pas de hiérarchie juste un niveau de classification.
* Contact de l’extérieur : arrive là dedans les fils créés par des extérieurs au groupe, soit par l’api, soit par l’import depuis une boite mail en pop, soit par un formulaire de contact
* Possibilité de déplacer un fil dans un sujet
* Possibilité de déplacer une réponse dans un autre fil
* Trace gardée des modifications
* Pas de possibilité d’effacement. On peut uniquement downvoter. Tout le monde possède la même “force” (un homme = une voix).
* L’onglet affiche le nombre de discussion non lues
* Possibilité d’importer un xml simple reprenant les discussions d’un système précédent (standard à définir)


##Onglet décisions
* Possibilité de lancer une proposition (avec date de cloture automatique?)
* Chacun vote comme sur loomio “D’accord, abstention, contre, bloque”
* Graphique reprenant les décisions
* L’onglet affiche le nombre de décisions sur lesquelles le participant n’a pas encore voté
* Certaines actions crées des décisions automatiquement (par exemple si on prévoit un système de newsletter, le fait d’envoyer la newsletter lance un processus de décision rapide pour lequel une partie des membres doivent donner leur accord afin d’envoyer une newsletter qui correspond au groupe). Dans les fait chacun peut initier une newsletter, mais il faut un accord pour l’envoyer effectivement. Cela se retrouve dans l’onglet décisions.
* En plus de voter, on peut ajouter un commentaire
* N’importe qui peut clôturer et n’importe qui peut rouvrir une décision?

##Onglet Calendrier
Calendrier collaboratif avec événements privés et publics. Les publics sont exportables  en ical et rss pour intégration ailleurs.

##Onglet Membres
* Liste des membres avec leur degré d’activité (dernière interaction par exemple).
* Contact perso d’un membre
* Un algorithme détermine les membres actifs et les inactifs en fonction de la vitesse du groupe.
* Bouton import liste de mails pour inviter les gens + abonnement à une lettre automatique de mise à jour.
* Export des membres

L’onglet affiche le nombre de membres en attente de validation. Un algorithme détermine combien de membres doivent donner leur accord pour qu’un membre soit approuvé (ou alors c’est dans la config du groupe).

Les membres peuvent être aussi de simples “abonnés” qui reçoivent des infos sur groupe par mail.

##Onglet wiki ou documents
Wiki hyper simple du type de celui de github. Remarkup comme Phabricator
cfr. https://secure.phabricator.com/book/phabricator/article/remarkup/

Si wysiwyg, ce serait bien un outil qui fait du inline editing avec juste quelques options (gras italique souligné titre 1 2 3 et liens). https://github.com/yabwe/medium-editor 

* Liste des documents, classement par date de mise à jour
* Boite de recherche dans les titres avec affichage rapide


##Onglet fichiers
S’inspirer de comment phabricator gère ça
Possibilité de dropper des fichiers là + ajouter des tags avec interface de recherche hyper simple et rapide. 

Evite les doublons si on reupload le même fichier.

* Chaque document a un ID
* On peut référencer chaque document par son ID dans n’importe quelle boite texte en mettant D123
* Quand un fichier est droppé dans une boite texte, il est uploadé dans la partie document, et référencé dans la boite textes avec le D123
* Impossible d’effacer un document
* Système de tags à ajouter aux documents



##Onglet “mon compte”
* Gestion du profil
* Abonnement mail : 1 par mois ou 1 par semaine ou 1 par jour (pas de choix plus compliqué). L’outil se charge de fabriquer et d’envoyer un mail récap super nickel à partir des infos du groupe.
* Plusieurs types de comptes
* membre candidat (suite à la demande d’un utilisateur de devenir membre)
* membre actif (interagit régulièrement)
* membre inactif (n’a plus donné signe de vie depuis longtemps) 
* abonné (reçoit juste les infos par mail de temps en temps,)



##Recherche
Recherche complète disponible pour tout le groupe (plein texte dans les documents wiki, les conversations et les événements)



Et c’est tout ! (mais c’est déjà beaucoup)

##Notes/idées/implantation
* Markup
* HTML ? ou remarkup partout
* https://secure.phabricator.com/book/phabricator/article/remarkup/
* tout est référençable par un identifiant dans le remarkup
    * E123 : lien vers événement
    * C534 : lien vers une conversation
    * D123 : lien vers une décision
    * F123 : lien vers un fichier
    * @membre : lien vers membre
    * [[wiki]]

Finalement plutôt wysiwyg simple partout. Avec ceci : https://github.com/yabwe/medium-editor 

Tags partout
Revision partout
Pas possible d’effacer (corbeille partout + trace)

##Fichiers
Pour l’administrateur du serveur, possibilité de configurer un stockage externe. En utilisant laravel flysystem, il semble possible que cela soit transparent au niveau dév.

Sur le serveur de fichiers : 
storage/[id du groupe]/[dossier par sous id]/[id du document] (avec dossiers pour éviter la limite système de fichiers)

Champs
id 
groupe id

##Utilisateur
Système auth de laravel
Social login
Pas de système de rôle
Système blindé qui vérifie qu’une ressource est accessible à un utilisateur (= l’utilisateur est membre du groupe) chaque fois qu’elle est accédée. (utilisateur loggué -> groupe(s) -> ressource)
Un utilisateur est soit candidat à un groupe soit membre effectif
Proposer quelques champs en plus (genre numéro de téléphone) en indiquant clairement que les membres des groupes dans lesquels on est pourront le consulter.
Gestion du temps
https://www.drupal.org/project/radioactivity
