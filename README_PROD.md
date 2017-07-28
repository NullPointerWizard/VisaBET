Ce fichier decrit les differentes etapes pour la mise en production.

# Initialisation
1. Cloner le repo a l'endroit voulu sur le serveur
```
git clone https://github.com/NullPointerWizard/VisaBET.git
```
1. Creation d'un virtualhost name-based avec Synology Webstation
    1. hostname: visa-moe.com
    1. port 80/443
    1. racine du document: [emplacementGitClone]
    1. serveur HTTP: Appache 2.2
    1. PHP: 5.6

3. Utilisation de Composer pour télécharger toutes les dépendances
    1. Installation de Composer
    ```
    curl -sS https://getcomposer.org/installer | php
    ```
    2. Installation des dépendances (bien utiliser php56)
    ```
    php56 composer.phar install
    ```

# Mise en production
1. Connexion en SSH au serveur sur lequel on déploie (avec PuTTY)
1. Se placer à la racine du dossier contenant le projet (sur Linux utiliser la commande "cd" )
1. Verifier le bon fonctionnement en local
    1. On change d'environnement dans l'url
    2. On peut activer le debugger dans l'environnement de production, dans web/app.php (false quand on a fini)
    ```php
    $kernel = new AppKernel('prod', true); // Définissez ce 2e argument à true
    ```
1. 
2. Nettoyage du cache de production (utiliser la 2e commande)
```
php bin/console cache:clear --env=prod
php56 bin/console cache:clear --env=prod --no-warmup
```

3. Mise à jour de la base de donnée
    1. Generation de la migration
    ```
    php bin/console doctrine:migrations:diff
    ```
    2. Modification éventuelles du fichier de migration généré
    3. Application des changements
    ```
    php bin/console doctrine:migrations:migrate
    ```
# Astuces
## Utiliser une autre version de php
* Le NAS a plusieurs versions de php installées, en console il faut en général utiliser
```
php56 [commande]
```

## Préparer l'application en local
* Changer d'environnement directement dans l'URL avec http://localhost:8000/app.php/ (prod) ou http://localhost:8000/app_dev.php/

## Forcer git à utiliser le dernier commit de master sur github (From [stackoverflow/questions](https://stackoverflow.com/questions/1125968/how-do-i-force-git-pull-to-overwrite-local-files))
```
git fetch --all
git reset --hard origin/master
//git reset --hard origin/<branch_name> pour une autre branche
```

## And on production ? (From [html2pdf doc](https://github.com/spipu/html2pdf/edit/master/doc/install.md))

You have **not** to install composer on your production server.

You have to install composer **only** on your dev environement. Composer is a dev tool.

To deliver you app on a server, you have to (on you dev environement) :

  * Git clone the tag/branch that you want to deliver
  * Launch the command `composer install --no-dev`
  * Remove the useless files (like the `.git` folder)
  * Zip all

That's all, you have a beautifull package that can be deliver on a server !
