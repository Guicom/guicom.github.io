<?php

namespace Drupal\soc_multisite\Commands;

use Drupal\Core\Database\Database;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class SocMultisiteCommands extends DrushCommands {

  /**
   * Command description here.
   *
   * @param $destinationSiteMachineName
   *   Machine name of the site to create.
   * @param $destinationSiteUri
   *   URI of the site to create.
   * @param $sourceSite
   *   Machine name of the site to copy.
   * @option option-name
   *   Description
   * @usage soc_multisite:generate_site source_site destination_site
   *   Usage description
   *
   * @command soc_multisite:generate_site
   * @aliases mgs
   */
  public function generateSite($destinationSiteMachineName, $destinationSiteUri, $sourceSite = 'default') {
    $database = Database::getConnectionInfo();
    $dbInfos = [
      'host' => $database['default']['host'],
      'port' => $database['default']['port'],
      'dbname' => $destinationSiteMachineName,
      'username' => $database['default']['username'],
      'password' => $database['default']['password'],
    ];

    \Drupal::service('soc_multisite.handler')->prepareSiteDirectory($destinationSiteMachineName, $destinationSiteUri, $dbInfos);

    $this->logger()->success(dt('Copying ' . $sourceSite . ' to ' . $destinationSiteMachineName . '.'));
  }

}
