
install:
	composer install
	./vendor/bin/phing install
	cd web
	sudo npm install --global gulp-cli
	sudo npm install --no-bin-link
	gulp

set-kubernetes-configs:
	cp /var/www/html/config/drupal/example.settings.kubernetes.php /var/www/html/config/drupal/settings.local.php

