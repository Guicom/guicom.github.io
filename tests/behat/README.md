# Configure Behat

You have to change two files for behat to work :

- config/drupal/settings.local.php

  - $settings['trusted_host_patterns'] = array('^.*$',); 

- tests/behat/behat.local.yml

  - base_url: http://web

# Launch a test on a tag

- You just have to launch the following command in the apache docker in the base folder :

  - ./vendor/bin/phing behat:run -Dbehat.tags={your_tag}