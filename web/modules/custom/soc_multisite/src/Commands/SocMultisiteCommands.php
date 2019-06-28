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
   * Scaffold architecture for a new site.
   *
   * @param $destinationSiteMachineName
   *   Machine name of the site to create.
   * @param $destinationSiteUri
   *   URI of the site to create.
   * @param $sourceSite
   *   Machine name of the site to copy.
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

    $this->logger()->info(dt('Copying ' . $sourceSite . ' to ' . $destinationSiteMachineName . '.'));
    try {
      \Drupal::service('soc_multisite.handler')
        ->prepareSiteDirectory($destinationSiteMachineName, $destinationSiteUri, $dbInfos);
    } catch (\Exception $e) {
      $this->logger()
        ->error(dt('Error creating ' . $destinationSiteMachineName . ' site directory.'));
    }
  }

  /**
   * Create config split for a new site.
   *
   * @param $destinationSiteMachineName
   *   Machine name of the site to create.
   * @usage soc_multisite:generate_site source_site destination_site
   *   Usage description
   *
   * @command soc_multisite:generate_config_split
   * @aliases mgcs
   */
  public function generateConfigSplit($destinationSiteMachineName) {
    $this->logger()->info(dt('Creating configuration split ' . $destinationSiteMachineName . '.'));
    try {
      \Drupal::service('soc_multisite.handler')
        ->createConfigSplit($destinationSiteMachineName);
    } catch (\Exception $e) {
      $this->logger()
        ->error(dt('Error creating configuration split ' . $destinationSiteMachineName . '.'));
    }
  }

}
