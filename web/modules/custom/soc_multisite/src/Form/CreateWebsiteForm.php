<?php

/**
 * @file
 * Contains \Drupal\soc_multisite\Form\CreateWebsiteForm.
 */

namespace Drupal\soc_multisite\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CreateWebsiteForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'create_website_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $multisiteService = \Drupal::service('soc_multisite.handler');
    $form['website_origin'] = [
      '#type' => 'select',
      '#title' => t('Site à dupliquer'),
      '#description' => t('Choisissez le site web à dupliquer'),
      '#options' => $multisiteService->getEnabledSites(),
      '#empty_option' => t('Choisir un site'),
      '#required' => TRUE,
    ];
    $form['website_infos'] = [
      '#type' => 'details',
      '#title' => t('Informations du site'),
      '#open' => TRUE,
      '#states' => [
        'invisible' => [
          ':input[name="website_origin"]' => ['value' => '']]
      ],
    ];
    $form['website_infos']['website_name'] = [
      '#type' => 'textfield',
      '#title' => t('Nom du site'),
      '#description' => t('Exemple : Socomec France'),
      '#required' => TRUE,
    ];

    $form['website_infos']['website_machine_name'] = [
      '#type' => 'textfield',
      '#title' => t('Nom machine'),
      '#description' => t('Exemple : socomec_france'),
      '#required' => TRUE,
    ];

    $form['website_infos']['website_fqdn'] = [
      '#type' => 'textfield',
      '#title' => t('FQDN'),
      '#description' => t('Exemple : www.socomec.fr'),
      '#required' => TRUE,
    ];

    $form['website_infos']['website_database'] = array(
      '#type' => 'fieldset',
      '#title' => t('Base de données'),
    );

    $form['website_infos']['website_database']['dbname'] = array(
      '#type' => 'textfield',
      '#title' => t('Base de données'),
      '#required' => TRUE,
    );

    $form['website_infos']['website_database']['username'] = array(
      '#type' => 'textfield',
      '#title' => t('Utilisateur'),
      '#required' => TRUE,
    );

    $form['website_infos']['website_database']['password'] = array(
      '#type' => 'textfield',
      '#title' => t('Mot de passe'),
      '#required' => TRUE,
    );

    $form['website_infos']['website_database']['host'] = array(
      '#type' => 'textfield',
      '#title' => t("Nom d'hôte"),
      '#required' => TRUE,
    );

    $form['website_infos']['website_database']['port'] = array(
      '#type' => 'textfield',
      '#title' => t('Port'),
      '#default_value' => '3306',
    );

    $form['website_infos']['website_base_theme'] = array(
      '#type' => 'select',
      '#title' => t('Thème à dupliquer'),
      '#description' => t('Choisissez le thème à dupliquer'),
      '#options' => $multisiteService->getCustomThemes(),
      '#empty_option' => t('Choisir un thème'),
      '#required' => TRUE,
    );

    $form['website_infos']['website_theme_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Thème à créer'),
      '#description' => t('Exemple : redpill'),
      '#required' => TRUE,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Créer'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $databaseHost = $values['host'];
    $databasePort = $values['port'];
    $databaseName = $values['dbname'];
    $dbError = FALSE;
    try {
      $dsn = "mysql:host=$databaseHost:$databasePort;dbname=$databaseName";
      $db = new \PDO($dsn, $values['username'], $values['password'], array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
    } catch (\PDOException $ex) {
      $dbError = TRUE;
    }
    if ($dbError) {
      $form_state->setErrorByName(NULL, t('Impossible de se connecter à la base de données. '
          . 'Veuillez vérifier les informations de connexion.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $siteName = $values['website_name'];
    $siteMachineName = $values['website_machine_name'];
    $siteDomain = $values['website_fqdn'];
    $dbInfos = [
      'host' => $values['host'],
      'port' => $values['port'],
      'dbname' => $values['dbname'],
      'username' => $values['username'],
      'password' => $values['password'],
    ];
    $sourceTheme = $values['website_base_theme'];
    $targetTheme = $values['website_theme_name'];
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
  }

}
