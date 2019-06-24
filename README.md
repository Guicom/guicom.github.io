# Socomec

## Installation :

``git clone git@gitlab.com:actency/socomec.git``

copy/paste docker-composer.exemple.yml

Manage your docker-sync.yml configuration if you are on Mac OS system.

copy/paste phing/exemple.build.proprieties.local to phing/build.proprieties.local.

run ``docker-compose up -d``

From the web container run ``composer install``

Then, run ``/vendor/bin/phing install``
