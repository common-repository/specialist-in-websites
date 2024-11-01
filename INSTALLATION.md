## Installatie instructies (Forge)

Zorg dat de installatie op Forge Gereed is.
De installatie op forge bestaat uit:
 
```
   my-project
   ├── website
   │   └── wp-content
   │       └── plugins
   │           └── specialist-in-websites
   │               ├── .git
   │               └── plugin.php
   └── svn
   │   ├── assets
   │   ├── branches
   │   ├── tags
   │   └── trunk
   │       ├── .git
   │       └── plugin.php
   └── temp-git
       ├── (Gecompileerde variant van de plug-in)
```
De deployment script moet het volgende uitvoeren:

1. CD naar de WP installatie <br />
   ```cd /home/forge/prd-plugin.siw-ontwikkeling.nl/website```<br /><br />
2. Draai composer om om bedrock up-to-date te houden <br />
   ```composer i```<br /><br />
3. CD naar de plug-in map binnen de WP installatie <br />
   ```cd /home/forge/prd-plugin.siw-ontwikkeling.nl/website/web/app/plugins/specialist-in-websites/```<br /><br />
4. Haalt via GIT de laatste versie van branch ```master``` op <br />
   ```git pull origin master --allow-unrelated-histories```<br /><br />
5. CD naar de ```temp-git``` folder
   ```cd /home/forge/prd-plugin.siw-ontwikkeling.nl/temp-git```
6. Haalt opnieuw via GIT de laatste versie van branch ```master``` op <br />
   ```git pull origin master --allow-unrelated-histories```<br /><br />
7. Draai de installatie scripts voor composer & NPM met tags voor de productie versie <br />
   ```composer install --no-dev```<br />
   ```npm install --production```
8. CD naar de svn folder <br />
   ```cd /home/forge/prd-plugin.siw-ontwikkeling.nl/svn```<br /><br />
9. Haal de trunk folder leeg <br />
   ```rm -rf *```<br /><br />
10. Synchroniseer de folder met een gecompileerde versie uit de temp-git folder <br />
    ```rsync -rc --exclude-from="../temp-git/.distignore" "../temp-git/" trunk/ --delete --delete-excluded```<br /><br />
11. Voeg de bestanden toe aan SVN control <br />
    ```svn add . --force``` <br />
    ```svn status | grep '^\!' | sed 's/! *//' | xargs -I% svn rm %@```<br /><br />
12. Haal op een of andere manier de nieuwste versie tag op <br />
    ``` ? ```<br /><br />
13. Maak (automatisch) een versie tag aan <br />
    ```svn cp "trunk" "tags/*```<br /><br />
14. Zorg dat de afbeeldingen MIME types goed zitten om te voorkomen dat afbeeldingen gedownload worden als je ze bekijkt <br />
    ```svn propset svn:mime-type image/png assets/*.png || true``` <br />
    ```svn propset svn:mime-type image/jpeg assets/*.jpg || true```<br /><br />
15. Verstuur SVN naar WordPress (? = changelog bericht?)<br />
    ```svn ci -m '?'```<br /><br />


