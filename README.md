# wifi_aprs

gebruikers rollen:
1. ROLE_ADMIN: superadmin
2. ROLE_USER: taco 
3. ROLE_TEAM: buitenlopers

info:
elk spel heeft zijn eigen project.
devices zijn buiten globaal buiten projecten om.
locaties zijn per project.
vraagen en antwoorden zijn per project


installatie:

voor de eerste keer:
1. composer install
2. npm install

startup
 1. symfony server:start
 2. npm run watch
 
 waneer nodig
 1. php bin/console doctrine:migrations:migrate voor de database update
 2. composer en npm install eens in de zoveel tijd waneer er mogelijk updates zijn geweest.
