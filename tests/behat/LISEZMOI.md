# Configurer Behat

Il faut changer les fichiers suivants pour que behat fonctionne en toute circonstance :

- config/drupal/settings.local.php

  - $settings['trusted_host_patterns'] = array('^.*$',); 

- tests/behat/behat.local.yml

  - base_url: http://web

# Lancer un test sur un tag

- Il faut lancer la commande suivante à la racine du projet depuis le docker apache :

  - ./vendor/bin/phing behat:run -Dbehat.tags={your_tag}