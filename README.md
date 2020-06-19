# orientation_lycee
PHP web site to resume students' choices for highschool in France


Pour utiliser ce site, il faut un hebergeur (ça peut se faire sur un ordi dans un réseau privé d'établissement), avec une base de données.

Téléchargez le dossier, décompressez-le. Dans le dossier décompresser, allez dans le dossier php, puis ouvrez le fichier connect.php avec un éditeur de texte.

Munissez-vous des codes pour accéder à la base de données (ils peuvent être récupérés dans la partie client de votre hébergeur).
Modifiez les variables en remplaçant le texte entre " ", mais laissez ceux-ci. Par exemple si votre lycée s'appelle Molière, dans la ligne 
$lycee = "Nom du lycée";
Écrivez :
$lycee = "Molière";

Vous pouvez également modifier le logo du lycée en remplaçant le fichier logo.png dans le dossier image par celui de votre lycée. Attention à bien renomer le logo de vorte lycée avec le nom logo.png
Vous pouvez également modifier le favicon.ico si vous le souhaitez.

Ensuite il y a 2 cas possibles :
- Vous avez créé un hébergement que pour ce site
Installez la totalité des fichiers dans le répertoire racine (c'est à dire là où il y a le fichier index.html ou index.php).
- Vous avez déjà un hébergement pour le site de votre lycée (par exemple monlycee.fr) et vous voulez mettre ce site dedans
Créer un répertoire (par exemple voeux) à la racine (c'est à dire là où il y a le fichier index.html ou index.php) et installez la totalité des fichiers dedans, pour accéder à ce site vous devrez alors taper monlycee.fr/voeux

Pour paramétrer la base de données, vous devez lancer le fichier setup.php, pour cela :
- si vous êtes dans le premier cas juste au-dessus, tapez le nom de domaine de votre site suivi de /setup.php, par exemple si votre nom de domaine est monlycee.fr, alors tapez monlycee.fr/setup.php
- si vous êtes dans le deuxième cas, tapez monlycee.fr/voeux/setup.php (remplacez monlycee.fr par votre nom de domaine).

Si un problème se déclare, c'est sûrement à cause d'un mauvais paramétrage du fichier connect.php, vérifiez vos identifiants de la base de données.
Sinon entrez votre compte admin, et entrez bien une adresse email (pour pouvoir récupérer votre mot de passe admin par mail en retournant sr la page setup.php).

Pour accèder au site taper l'adresse nomdedomaine.fr/voeux
Rentrer vos identifiants admin et vous pouvez commencer à créer des classes en allant dans la Partie Gestion Prof.
Pour la création des classe seules les nom 2A, 2B,… et 1A, 1B,… sont acceptés (ils sont utilisés pour afficher le site).
