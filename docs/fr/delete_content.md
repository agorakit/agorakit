# Suppression de contenu
La suppression de contenu sur Agorakit respecte une procédure qui permet de répondre à différents impératifs:

- le droit de chacun et chacune à supprimer ses productions
- permettre de revenir en arrière en cas de fausse manoeuvre
- permettre de changer d'avis pendant un certain temps
- garder les contributions collectives en cas de départ d'un participant


Quand un contenu est effacé, celui-ci est marqué comme effacé dans la base de donnée et n'apparait plus. Un administrateur d'instance peut remettre en ligne un contenu effacé en se rendant dans le menu "admin > récupérer du contenu".

Le contenu marqué comme effacé est définitivement effacé (physiquement) de la base de donnée après 30 jours. Il n'est plus possible après ce délai de récupérer le contenu.


# Suppression de compte utilisateur
Si un utilisateur décide d'effacer son compte, il peut au choix : 

- anonymiser son contenu (tout son contenu est transféré à un utilisateur anonyme)
- demander que tout le contenu qu'il a produit soit effacé

Les discussions qui ont des commentaires sont dans tous les cas assignées à l'utilisateur anonyme afin de ne pas perdre les contributions des autres utilisateurs. 
Il n'est pas possible d'effacer individuellement une discussion sous laquelle il y a des commentaires.

Un utilisateur qui est le seul administrateur d'un groupe ne peux pas quitter le groupe.


# Nettoyage de la base de donnée
Si Agorakit est installé correctement (avec les tâches "cron" récurentes), la base de donnée va être automatiquement nettoyée afin d'être en conformité avec ce qui est indiqué ci-dessus.
Le nombre de jours de rétention des informations avant suppression définitive est configurable et fixé par défaut à 30.

# Restauration d'un élément effacé
Un administrateur d'instance peut restaurer tout contenu effacé pendant la période de rétention (30 jours par défaut). Il suffit de se rendre dans `Admin > settings > recover content` et de choisir le ou les éléments à restaurer.

