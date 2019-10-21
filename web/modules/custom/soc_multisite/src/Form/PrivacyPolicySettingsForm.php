<?php

namespace Drupal\soc_multisite\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class PrivacyPolicySettingsForm extends ConfigFormBase {

  /** @var string  */
  const WS_SETTINGS_KEY = 'soc_multisite.privacy_policy';

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return [
      self::WS_SETTINGS_KEY,
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
    return str_replace('.', '_', self::WS_SETTINGS_KEY);
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::WS_SETTINGS_KEY);

    $form['privacy_policy'] = [
      '#type'           => 'container',
      '#title'          => $this->t('Default Message'),
    ];

    $form['privacy_policy']['text_default'] = [
      '#type'           => 'textarea',
      '#title'          => $this->t('Text Privacy Policy'),
      '#description'    => $this->t('Privacy Policy Default Text'),
      '#default_value'  => empty($config->get('text_default')) !=true ? t($config->get('text_default')):'',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ([
               'text_default',
             ] as $configKey) {
      $this->configFactory->getEditable(self::WS_SETTINGS_KEY)
        ->set($configKey, $form_state->getValue($configKey))
        ->save();
    }
    parent::submitForm($form, $form_state);
  }
}
