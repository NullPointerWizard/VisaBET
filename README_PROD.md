Ce fichier decrit les differentes etapes pour la mise en production.

# Initialisation
1. 

# Mise en production
1. Verifier le bon fonctionnement en local
    1. On change d'environnement dans l'url
    1. On peut activer le debugger dans l'environnement de production, dans web/app.php (false quand on a fini)
    ```php
    $kernel = new AppKernel('prod', true); // Définissez ce 2e argument à true
    ```
1. Nettoyage du cache de production
'''
php bin/console cache:clear --env=prod
'''
1. Item 2
1. Item 3
   1. Item 3a
   1. Item 3b


# Astuces
## Préparer l'application en local
* Changer d'environnement directement dans l'URL avec http://localhost:8000/app.php/ (prod) ou http://localhost:8000/app_dev.php/
```

```
