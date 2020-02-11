
install:
	composer install
	./vendor/bin/phing install
	./vendor/bin/phing gulp-socomec:install
	./vendor/bin/phing gulp-socomec:css

set-kubernetes-configs:
	cp /var/www/html/config/drupal/example.settings.kubernetes.php /var/www/html/config/drupal/settings.local.php

drupal-update:
	./vendor/bin/phing update
	./vendor/bin/phing content-import-all
	./vendor/bin/phing megamenu-socomec:import
	./vendor/bin/phing admin-socomec:add-role
	./vendor/bin/phing gulp-socomec:css
	./vendor/bin/phing gulp-socomec:clear-cache


.PHONY: behat-event
behat-event:
	./vendor/bin/phing behat:run -Dbehat.tags=wip
