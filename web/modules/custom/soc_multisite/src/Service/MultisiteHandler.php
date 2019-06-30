<?php

namespace Drupal\soc_multisite\Service;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Site\Settings;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class MultisiteHandler {

  /**
   * Create directories and files for a new Drupal site.
   *
   * @param $siteMachineName
   * @param $siteDomain
   * @param $dbInfos
   *
   * @throws \Exception
   */
  public function prepareSiteDirectory($siteMachineName, $siteDomain, $dbInfos) {
    $app_root = \Drupal::root();
    try {
      $fileSystem = new Filesystem();
      // Clone template folder.
      $fileSystem->mirror($app_root . '/sites/template', $app_root . '/sites/' . $siteMachineName);
      // Clone template config folder.
      $fileSystem->mirror($app_root . '/../config/drupal/template', $app_root . '/../config/drupal/' . $siteMachineName);
      // Create sites.php if not existing
      $sitesFile = $app_root . '/sites/sites.php';
      if (!file_exists($sitesFile)) {
        $fileSystem->copy($app_root . '/sites/example.sites.php', $sitesFile);
        $trusted_host_patterns = Settings::get('trusted_host_patterns');
        $host = str_replace(['^', '\\', '$'], [], reset($trusted_host_patterns));
        $fileSystem->appendToFile($sitesFile, PHP_EOL . '$sites["' . $host . '"] = "default";');
      }
      require $app_root . '/sites/sites.php';
      if (!array_key_exists($siteDomain, $sites)) {
        // Add site to $sites variable.
        $fileSystem->appendToFile($app_root . '/sites/sites.php', PHP_EOL . '$sites["' . $siteDomain . '"] = "' . $siteMachineName . '";');
      }
      // Set $_site_name variable.
      $settingsPath = $app_root . '/sites/' . $siteMachineName . '/settings.php';
      $fileSystem->appendToFile($settingsPath, PHP_EOL . '$_site_name = "' . $siteMachineName . '";');
      $fileSystem->appendToFile($settingsPath, PHP_EOL . 'include "$app_ground/config/drupal/$_site_name/settings.local.php";');
      // Add Database infos.
      $settingsLocalPath = $app_root . '/../config/drupal/' . $siteMachineName . '/settings.local.php';
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '$databases["default"]["default"] = array (');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '  "database" => "' . $dbInfos['dbname'] . '",');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '  "username" => "' . $dbInfos['username'] . '",');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '  "password" => "' . $dbInfos['password'] . '",');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '  "prefix" => "",');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '  "host" => "' . $dbInfos['host'] . '",');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '  "port" => "' . $dbInfos['port'] . '",');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '  "namespace" => "Drupal\\Core\\Database\\Driver\\mysql",');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . '  "driver" => "mysql",');
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . ');');
      // Add trusted host patterns.
      $siteDomainRegex = '^' . str_replace('.', '\.', $siteDomain) . '$';
      $settingsTHP = '$settings["trusted_host_patterns"] = ["' . $siteDomainRegex . '"];';
      $fileSystem->appendToFile($settingsLocalPath, PHP_EOL . $settingsTHP);
    } catch (IOExceptionInterface $e) {
      throw new \Exception('Unable to prepare site directory.');
    }
  }

  /**
   * Create configuration splits for a new Drupal site.
   *
   * @param $destinationSiteMachineName
   *
   */
  public function createConfigSplit($destinationSiteMachineName) {
    $languageSplitCreated = FALSE;
    $entityTypeManager = \Drupal::service('entity_type.manager');
    $modelSiteMachineName = 'socomec_fr';
    /** @var \Drupal\config_split\Entity\ConfigSplitEntity $languageSplitModel */
    $languageSplitModel = $entityTypeManager->getStorage('config_split')->load($modelSiteMachineName);
    $languageSplit = $languageSplitModel->createDuplicate();
    $languageSplit->set('id', $destinationSiteMachineName);
    $languageSplit->set('label', $destinationSiteMachineName);
    $languageSplit->set('description', str_replace($modelSiteMachineName, $destinationSiteMachineName, $languageSplit->get('description')));
    $languageSplit->set('folder', str_replace($modelSiteMachineName, $destinationSiteMachineName, $languageSplit->get('folder')));
    try {
      $languageSplitCreated = $languageSplit->save();
    } catch (EntityStorageException $e) {
      \Drupal::logger('soc_multisite')->error($e->getMessage());
    }
    // Try to create an override config
    if ($languageSplitCreated !== FALSE && strstr($destinationSiteMachineName, 'socomec_')) {
      $modelOverrideName = str_replace('socomec_', 'overrides_', $modelSiteMachineName); // overrides_fr
      $destinationOverrideName = str_replace('socomec_', 'overrides_', $destinationSiteMachineName); // overrides_de
      /** @var \Drupal\config_split\Entity\ConfigSplitEntity $languageSplitModel */
      $languageOverrideSplitModel = $entityTypeManager->getStorage('config_split')->load($modelOverrideName);
      $languageOverrideSplit = $languageOverrideSplitModel->createDuplicate();
      $languageOverrideSplit->set('id', $destinationOverrideName);
      $languageOverrideSplit->set('label', $destinationOverrideName);
      $languageOverrideSplit->set('description', str_replace(
        [$modelSiteMachineName, $modelOverrideName],
        [$destinationSiteMachineName, $destinationOverrideName],
        $languageOverrideSplit->get('description')
      ));
      $languageOverrideSplit->set('folder', str_replace(
        [$modelSiteMachineName, $modelOverrideName],
        [$destinationSiteMachineName, $destinationOverrideName],
        $languageOverrideSplit->get('folder')
      ));
      try {
        $languageOverrideSplit->save();
      } catch (\Exception $e) {
        \Drupal::logger('soc_multisite')->error($e->getMessage());
      }
    }
  }

}
