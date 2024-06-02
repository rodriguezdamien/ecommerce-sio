![gaku!](https://github.com/rodriguezdamien/ecommerce-sio/blob/448e990790f9af45fb144e343a330747621f0ba3/illustration.png)
# gaku! - Site e-Commerce

Site Web e-commerce réalisé dans le cadre du BTS SIO.

La thématique est celui du "doujin".

## Lancer le projet en local (Pile LAMP)

-   Mettez le projet dans votre dossier dans le dossier racine d'Apache (htdocs sur XAMPP ou www sur WAMP)
-   Créez une virtual host au nom que vous souhaitez(`gaku.local` par défaut) pointant vers le dossier du projet

```conf
<VirtualHost *:80>
    DocumentRoot "C:\xampp\htdocs\ecommerce-sio"
    ServerName gaku.local
</VirtualHost>
```

-   Si vous souhaitez avoir une URL pour les fichiers publiques :
    -   crééez-en un deuxième au nom que vous souhaitez (`cdn.gaku.local` dans la configuration par défaut) et faites-le pointer vers le dossier "public" du projet.
    -   Ajoutez dans votre fichier de config des vhosts (`.../apache/conf/extra/httpd-vhosts.conf`) de Apache ceci :
    ```conf
    <VirtualHost *:80>
      DocumentRoot "C:\xampp\htdocs\ecommerce-sio\public"
      ServerName cdn.gaku.local
      Header set Access-Control-Allow-Origin "http://gaku.local"
    </VirtualHost>
    ```
    Enfin, mettez l'url de votre virtual host dans la constante `CDN_URL` présente dans le fichier `index.php`
    ```php
    //Ligne 13
    define('CDN_URL', 'http://cdn.gaku.local');
    ```
-   Si vous ne souhaitez pas faire de virtual host pour les fichiers publiques :

    -   Donnez la valeur `'/public/'` à la constante `CDN_URL` présente dans le fichier `index.php`

    ```php
    //Ligne 13
    define('CDN_URL', '/public/');
    ```

-   Il ne vous reste plus qu'à lancer le script SQL "`script.sql`" dans votre système de gestion de base de données SQL.
