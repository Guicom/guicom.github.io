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
    $sourceTheme = 'socomec';
    $targetTheme = $siteMachineName;

    \Drupal::service('soc_multisite.handler')->prepareSiteDirectory($siteMachineName, $siteDomain, $siteName, $dbInfos);

    /*
    $batch = array(
      'title' => t('Création du nouveau site @siteName', ['@siteName' => $siteName]),
      'operations' => array(
        array(
          'soc_multisite_batch_operation_copy_template',
          array(
            $siteMachineName,
            $siteDomain,
            $siteName,
            $dbInfos,
          ),
        ),
        array(
          'soc_multisite_batch_operation_site_install',
          array(
            $siteName,
            $siteDomain,
          )
        ),
        array(
          'soc_multisite_batch_operation_import_config',
          array(
            $siteDomain,
          )
        ),
        array(
          'soc_multisite_batch_operation_clone_theme',
          array(
            $siteDomain,
            $sourceTheme,
            $targetTheme,
          )
        ),
      ),
      'finished' => 'soc_multisite_finish_clone_site',
      'file' => drupal_get_path('module', 'soc_multisite') . '/soc_multisite.batch.inc',
    );
    batch_set($batch);
    */
    $this->logger()->success(dt('Copying ' . $sourceSite . ' to ' . $destinationSite . '.'));
  }

  /**
   * Command description here.
   *
   * @param $arg1
   *   Argument description.
   * @param array $options
   *   An associative array of options whose values come from cli, aliases, config, etc.
   * @option option-name
   *   Description
   * @usage soc_multisite-commandName foo
   *   Usage description
   *
   * @command soc_multisite:commandName
   * @aliases foo
   */
  public function commandName($arg1, $options = ['option-name' => 'default']) {
    $this->logger()->success(dt('Achievement ' . $arg1 . ' unlocked.'));
  }

  /**
   * An example of the table output format.
   *
   * @param array $options An associative array of options whose values come from cli, aliases, config, etc.
   *
   * @field-labels
   *   group: Group
   *   token: Token
   *   name: Name
   * @default-fields group,token,name
   *
   * @command soc_multisite:token
   * @aliases token
   *
   * @filter-default-field name
   * @return \Consolidation\OutputFormatters\StructuredData\RowsOfFields
   */
  public function token($options = ['format' => 'table']) {
    $all = \Drupal::token()->getInfo();
    foreach ($all['tokens'] as $group => $tokens) {
      foreach ($tokens as $key => $token) {
        $rows[] = [
          'group' => $group,
          'token' => $key,
          'name' => $token['name'],
        ];
      }
    }
    return new RowsOfFields($rows);
  }
}
