Le projet risque de passer en GIT (voir sur GITHUB pour être plus précis) pour la version 2.0 a moins que certains ne s'y opposent ...
Dans ce cas merci de poser vos noms / pseudo ci-dessous :
	Pour :
		- holblin
		- malodavid
		- remy ( petit code qui peut servir : http://kimura.univ-montp2.fr:8080/projects/linux-util/wiki/gititude (remarques bienvenues))
		- rififi (dans un trou à Paris) :  nous pouvons héberger le dépôt git s'il fallait.
	Contre :
		-
		
Si le projet part sur GIT dans ce cas merci de lire cette documentation.
Cela peux d'ailleurs vous permettre de mieux comprendre le sujet et de vous aider a réponde de façon pertinente ci-dessus.
	http://djibril.developpez.com/tutoriels/conception/pro-git/
	
Il faut absolument tester TOUTES les fonctionnalités présentes dans la version actuelle pour publier la 1.6 !
[ rififi: c'est ben vrai ça ! 
Car il y encore des bugs à corriger dans la 1.6, surtout quand on est en LDAP ...]
Merci de mettre votre nom ci-dessous si vous avez suffisamment testé l'appli dans tous les sens et qu'il n'y a pas de bug d'après vous.
Merci de le mettre QUE si vous AVEZ TOUT TESTE et que votre dépôt est à jour !!!
	- 

La version 1.6 DOIT être mise en bêta le plus rapidement possible.
Cette version sera la dernière version avec cette architecture.
Du coup, AUCUNE nouvelle fonctionnalité ne PEUX être AJOUTéE, seuls les bug fix peuvent être commités !!!

--------------------------------------------------------------------------------------------------------------------------
La version 2.0 va BIENTÔT devenir la version principale.

Pour cette version nous partirons d'une FEUILLE BLANCHE, nous utiliserons un MODEL OBJECT, nous reverrons la BDD !
Pour cette version, JQUERY & JQUERYUI seront fortement utilisé. (malodavid => OK ++)
Pour cette version, PHP 5.3 sera requis au minimum.
Pour cette version, le moteur InnoDB de MYSQL sera requis.
Pour cette version, un FRAMEWORK sera très probablement utilisé.
Pour mettre votre avis sur le framework a prendre merci de compléter ci dessous :
	- holblin => pas encore décidé (Symphonie / Zend / fait maison)
	- malodavid => Zend (petit +)
	- remy => quitte a repartir de 0, Quid de MariaDB ou PostgreSQL ? (réponse de holblin, SQL92 plutôt dans ce cas ? avec un petit PDO !)
	- rififi => et Django ? (Non ? Bon , alors je demande son avis sur vos propositions à mon conseiller en Frameworks ...)


--------------------------------------------------------------------------------------------------------------------------
Questions sur la 2.0 :

	- rififi: la spécif de la BDD de la 2.0 permettra t'elle d'importer des données d'une version 1.x ?
	  ( c'est absolument nécessaire pour tous les sites déjà en production )
	-  rififi: la version 2.0 aura t'elle des messages avec une meilleure orthographe (et langue) que la 1.x ?
          ( C'était le défaut No 1 des versions 1.x (x < 6), bien avant la qualité du code !)

--------------------------------------------------------------------------------------------------------------------------
MERCI de PARTICIPER, je me sentirais moins seul dans ce projet (je sais que certains sont occupés ... participer coûte rien).