**TWOUITER**

Voici les étapes a effectuer pour lancer le projet :

1 - Installer les modules

composer install

2 - Vérification du lien avec la BDD
 
Renseigner / Vérifier dans le fichier '.env' l'url de connexion vers la base de données

3 - Loader les fixtures

php bin/console doctrine:fixtures:load

4 - Générer les clefs JWT

php bin/console lexik:jwt:generate-keypair

5 - Lancer le serveur

symfony server:start --port=80
