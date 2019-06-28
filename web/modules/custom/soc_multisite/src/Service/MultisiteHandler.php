<?php

namespace Drupal\soc_multisite\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Site\Settings;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Finder\Finder;

class MultisiteHandler {

  private $envVariables = [
    'HOME' => '/var/www',
    'PATH' => '/usr/local/bin:/usr/bin:/bin:/usr/local/games:/usr/games:/var/www/.composer/vendor/bamarni/symfony-console-autocomplete/',
  ];

  /**
   * Get enabled sites.
   *
   * @return array
   */
  public static function getEnabledSites() {
    $app_root = \Drupal::root();
    // Determine whether multi-site functionality is enabled.
    $sitesFile = $app_root . '/sites/sites.php';
    if (!file_exists($sitesFile)) {
      return [];
    }

    require $app_root . '/sites/sites.php';
    $list = [];
    if (sizeof($sites)) {
      foreach ($sites as $domain => $siteName) {
        $list[$siteName] = $siteName;
      }
    }

    return $list;
  }

  /**
   * Get custom themes.
   *
   * @return array
   */
  public static function getCustomThemes() {
    $app_root = \Drupal::root();
    $finder = new Finder();
    $directories = $finder->in($app_root . '/themes/custom')->depth(0)->directories();
    $themes = [];
    foreach ($directories as $theme) {
      $themeName = $theme->getRelativePathname();
      $themes[$themeName] = $themeName;
    }
    return $themes;
  }

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
      // Add site to $sites variable.
      $fileSystem->appendToFile($app_root . '/sites/sites.php', PHP_EOL . '$sites["' . $siteDomain . '"] = "' . $siteMachineName . '";');
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
      } catch (EntityStorageException $e) {
        \Drupal::logger('soc_multisite')->error($e->getMessage());
      }
    }
  }

  /**
   * @param $siteDomain
   * @param $command
   */
  public function execConsoleCommand($siteDomain, $command) {
    $app_root = \Drupal::root();
    $process = new Process(
      "../vendor/bin/drupal --uri=http://$siteDomain $command", $app_root, $this->envVariables, NULL, NULL
    );
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }
  }

  /**
   * @param $task
   */
  public function execPhingTask($task) {
    $app_root = \Drupal::root();
    $process = new Process(
      "./vendor/bin/phing $task", $app_root . '/..', $this->envVariables, NULL, NULL
    );
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }
  }

  /**
   * @param $siteName
   * @param $siteDomain
   */
  public function siteInstall($siteName, $siteDomain) {
    $command = '--site-name="' . $siteName . '" site:install standard --force --no-interaction --langcode="fr"';
    $this->execConsoleCommand($siteDomain, $command);
  }

  /**
   * @param $siteDomain
   */
  public function cacheRebuildAll($siteDomain) {
    $command = 'cache:rebuild all';
    $this->execConsoleCommand($siteDomain, $command);
  }

  /**
   * @param $siteDomain
   */
  public function importConfigurations($siteDomain) {
    // Set UUID.
    $command = 'config:override system.site uuid d74caedc-cdeb-485d-9401-1d3c898a1a1c';
    $this->execConsoleCommand($siteDomain, $command);
    // Delete Shortcut Set.
    $command = 'entity:delete shortcut_set default';
    $this->execConsoleCommand($siteDomain, $command);
    // Import configurations.
    $command = 'config:import --directory=/var/www/html/config/drupal/sync';
    $this->execConsoleCommand($siteDomain, $command);
    // Clear cache.
    $this->cacheRebuildAll($siteDomain);
  }

  /**
   * @param $siteDomain
   * @param $sourceTheme
   * @param $targetTheme
   */
  public function cloneTheme($siteDomain, $sourceTheme, $targetTheme) {
    // Clone existing theme.
    $task = "theme:clone -Dsource=$sourceTheme -Dtarget=$targetTheme";
    $this->execPhingTask($task);
    // Clear cache.
    $this->cacheRebuildAll($siteDomain);
    // Enable new theme.
    $command = "theme:install $targetTheme --set-default";
    $this->execConsoleCommand($siteDomain, $command);
  }

}
