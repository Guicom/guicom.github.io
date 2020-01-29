
install:
	composer install
	./vendor/bin/phing install
	./vendor/bin/phing gulp-socomec:install
	./vendor/bin/phing gulp-socomec:css

set-kubernetes-configs:
	cp /var/www/html/config/drupal/example.settings.kubernetes.php /var/www/html/config/drupal/settings.local.php

drupal-update:
	./vendor/bin/phing update
