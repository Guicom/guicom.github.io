
install:
	composer install
	./vendor/bin/phing install
	./vendor/bin/phing gulp-socomec:install
	./vendor/bin/phing gulp-socomec:css

set-kubernetes-configs:
	cp /var/www/html/config/drupal/example.settings.kubernetes.${ENVIRONMENT}.php /var/www/html/config/drupal/settings.local.php
	cp /var/www/html/config/drupal/contenthub/example.settings.kubernetes.${ENVIRONMENT}.php /var/www/html/config/drupal/contenthub/settings.local.php
	cp /var/www/html/web/sites/sites.${ENVIRONMENT}.php /var/www/html/web/sites/sites.php
	chmod g+r /var/www/html/web/sites/sites.php /var/www/html/config/drupal/contenthub/settings.local.php /var/www/html/config/drupal/settings.local.php
	chown web:www-data /var/www/html/web/sites/sites.php /var/www/html/config/drupal/contenthub/settings.local.php /var/www/html/config/drupal/settings.local.php

drupal-update:
	contenthub_uri=$$(php -r "include('/var/www/html/web/sites/sites.php'); print array_search('contenthub', \$$sites);")
	./vendor/bin/phing update
	./vendor/bin/phing content-import-all || true
	./vendor/bin/phing megamenu-socomec:import || true
	./vendor/bin/phing admin-socomec:add-role
	./vendor/bin/phing solr:cr
	./vendor/bin/phing gulp-socomec:css
	./vendor/bin/phing gulp-socomec:clear-cache
	./vendor/bin/phing drush:cc


.PHONY: behat-event
behat-event:
	./vendor/bin/phing behat:run -Dbehat.tags=wip
