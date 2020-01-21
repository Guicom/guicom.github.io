
install:
	composer install
	./vendor/bin/phing install
	npm install --global gulp-cli
    npm install --no-bin-link
    cd web
    gulp

set-kubernetes-configs:
	cp /var/www/html/config/drupal/example.settings.kubernetes.php /var/www/html/config/drupal/settings.local.php
