<?php

namespace Drupal\soc_nextpage\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Site\Settings;

/**
 * Class NextpageApiSettingsForm
 *
 * @package Drupal\soc_nextpage\Form
 */
class NextpageApiSettingsForm extends ConfigFormBase {

  const WS_SETTINGS_KEY = 'soc_nextpage.nextpage_ws';

  const DEFAULT_BASE_URL = 'http://socomec-dummies.actency.fr/';
  const DEFAULT_USERNAME = 'admin';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return str_replace('.', '_', self::WS_SETTINGS_KEY);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      self::WS_SETTINGS_KEY,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::WS_SETTINGS_KEY);

    $form['global'] = [
      '#type'           => 'fieldset',
      '#title'          => $this->t('Global'),
    ];

    $form['global']['base_url'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Base URL'),
      '#description'    => $this->t('The base URL to use to request nextPage.'),
      '#default_value'  => $config->get('base_url') ??
        Settings::get('soc_nextpage_base_url', self::DEFAULT_BASE_URL),
    ];

    $form['auth'] = [
      '#type'           => 'fieldset',
      '#title'          => $this->t('Authentication'),
    ];

    $form['auth']['username'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Username'),
      '#description'    => $this->t('The username to use to request nextPage.'),
      '#default_value'  => $config->get('username') ??
        Settings::get('soc_nextpage_username', self::DEFAULT_USERNAME),
    ];

    $form['auth']['password'] = [
      '#type'           => 'password',
      '#title'          => $this->t('Password'),
      '#description'    => $this->t('The password to use to request nextPage.'),
      '#default_value'  => $config->get('password') ??
        Settings::get('soc_nextpage_password', ''),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ([
               'base_url',
               'username',
               'password',] as $configKey) {
      $this->configFactory->getEditable(self::WS_SETTINGS_KEY)
        ->set($configKey, $form_state->getValue($configKey))
        ->save();
    }

    parent::submitForm($form, $form_state);
  }
}
