<?php

namespace Drupal\soc_multisite\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drupal\Core\Database\Database;
use Drupal\Core\Site\Settings;
use Drupal\Core\Transliteration\PhpTransliteration;
use Drupal\token\Token;
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
   * @param $destinationSite
   *   Site to create.
   * @param $sourceSite
   *   Site to copy.
   * @option option-name
   *   Description
   * @usage soc_multisite:generate_site source_site destination_site
   *   Usage description
   *
   * @command soc_multisite:generate_site
   * @aliases mgs
   */
  public function generateSite($destinationSite, $sourceSite = 'default') {
    $transliterator = new PHPTransliteration(NULL, \Drupal::moduleHandler());
    $siteName = $destinationSite;
    $siteMachineName = $transliterator->transliterate($siteName);
    $siteDomain = str_replace('_', '.', $siteMachineName) . '.loc';
    $database = Database::getConnectionInfo();
    $dbInfos = [
      'host' => $database['default']['host'],
      'port' => $database['default']['port'],
      'dbname' => $siteMachineName,
      'username' => $database['default']['username'],
      'password' => $database['default']['password'],
    ];

    \Drupal::service('soc_multisite.handler')->prepareSiteDirectory($siteMachineName, $siteDomain, $siteName, $dbInfos);

    $this->logger()->success(dt('Copying ' . $sourceSite . ' to ' . $destinationSite . '.'));
  }

}
