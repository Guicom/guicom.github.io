<?php

function soc_multisite_batch_operation_copy_template($siteMachineName, $siteDomain, $siteName, $dbInfos, &$context) {
  sleep(1);
  \drupal::service('soc_multisite.handler')->prepareSiteDirectory($siteMachineName, $siteDomain, $siteName, $dbInfos);
  $context['results']['site_domain'] = $siteDomain;
  $context['message'] = t('Copie du dossier template de site');
}

function soc_multisite_batch_operation_site_install($siteName, $siteDomain, &$context) {
  \drupal::service('soc_multisite.handler')->siteInstall($siteName, $siteDomain);

  $context['message'] = t('Installation du site');
}

function soc_multisite_batch_operation_import_config($siteDomain, &$context) {
  \drupal::service('soc_multisite.handler')->importConfigurations($siteDomain);

  $context['message'] = t('Importation des configurations');
}

function soc_multisite_batch_operation_clone_theme($siteDomain, $sourceTheme, $targetTheme, &$context) {
  \drupal::service('soc_multisite.handler')->cloneTheme($siteDomain, $sourceTheme, $targetTheme);

  $context['message'] = t('Création et activation du nouveau thème');
  $context['finished'] = TRUE;
}

function soc_multisite_finish_clone_site($success, $results, $operations) {
  $messenger = \Drupal::messenger();
  if ($success) {
    $messenger->addMessage(t('Site créé avec succès : @site.', ['@site' => 'http://' . $results['site_domain']]));
  }
  else {
    // An error occurred.
    // $operations contains the operations that remained unprocessed.
    $error_operation = reset($operations);
    $messenger->addMessage(
      t('An error occurred while processing @operation with arguments : @args',
        [
          '@operation' => $error_operation[0],
          '@args' => print_r($error_operation[0], TRUE),
        ]
      )
    );
  }
}
