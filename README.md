**TWOUITER**

Voici les étapes a effectuer pour lancer le projet :

1 - Installer les modules

composer install

2 - Vérification du lien avec la BDD
 
Renseigner / Vérifier dans le fichier '.env' l'url de connexion vers la base de données

3 - Loader les fixtures

php bin/console doctrine:fixtures:load

Login example : helldiver0@democracy.com

Password example : password0

4 - Générer les clefs JWT

php bin/console lexik:jwt:generate-keypair

5 - Configurer les tests
Configurer les tests (notamment sur la BDD de test)

symfony console secrets:set AKISMET_KEY --env=test

php bin/console doctrine:schema:create --env=test

symfony console doctrine:migrations:migrate -n --env=test

symfony console doctrine:fixtures:load --env=test


Pour les executer :

symfony php bin/phpunit

6 - Lancer le serveur

symfony server:start --port=80
