<?php

namespace Drupal\soc_pardot\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class PrivacyPolicySettingsForm extends ConfigFormBase {

  /** @var string  */
  const PARDOT_SETTINGS_KEY = 'soc_pardot.privacy_policy';

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return [
      self::PARDOT_SETTINGS_KEY,
    ];
  }

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return str_replace('.', '_', self::PARDOT_SETTINGS_KEY);
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::PARDOT_SETTINGS_KEY);

    $form['privacy_policy'] = [
      '#type'           => 'details',
      '#open'           => TRUE,
      '#title'          => $this->t('Privacy policy default text'),
    ];

    $form['privacy_policy']['text_default'] = [
      '#type'           => 'text_format',
      '#title'          => $this->t('Text Privacy Policy'),
      '#description'    => $this->t('Privacy Policy Default Text'),
      '#format'        => empty($config->get('text_default')['format']) != true ? $config->get('text_default')['format']: 'socomec',
      '#default_value'  => empty($config->get('text_default')['value']) != true ? $config->get('text_default')['value'] : '',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach (['text_default'] as $configKey) {
      $this->configFactory->getEditable(self::PARDOT_SETTINGS_KEY)
        ->set($configKey, $form_state->getValue($configKey))
        ->save();
    }
    parent::submitForm($form, $form_state);
  }
}
