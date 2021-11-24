# Creation de la base de donnée :
Edit du DATABASE_URL dans le .env

`DATABASE_URL=mysql://user:pass@host:3306/dbName?serverVersion=5.7`

Effectuer la commande si dessous

`$ php bin/console doctrine:database:create`

