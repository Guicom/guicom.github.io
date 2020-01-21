
install:
	composer install
	./vendor/bin/phing install

set-kubernetes-configs:
	cp /var/www/html/config/drupal/example.settings.kubernetes.php /var/www/html/config/drupal/settings.local.php
