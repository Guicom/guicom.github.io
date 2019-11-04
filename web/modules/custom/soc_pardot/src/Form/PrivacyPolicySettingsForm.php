<?php

namespace Drupal\soc_pardot\Form;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PrivacyPolicySettingsForm extends ConfigFormBase {

  /** @var string  */
  const PARDOT_SETTINGS_KEY = 'soc_pardot.privacy_policy';
  
  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;
  
  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The language manager.
   */
  public function __construct(LanguageManagerInterface $languageManager) {
    $this->languageManager = $languageManager;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('language_manager')
    );
  }
  
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
    $languages = $this->languageManager->getLanguages();
    
    foreach ($languages as $lang) {
      
      $form['langue_' . $lang->getId()]['privacy_policy_' . $lang->getId()] = [
        '#type'           => 'details',
        '#open'           => FALSE,
        '#title'          => $lang->getName(),
      ];
  
      // Default value.
      $d_value = empty($config->get('text_default_' . $lang->getId())['format'])
      != true ? $config->get('text_default_' . $lang->getId())['format']: 'socomec';
      // Default format.
      $d_format = empty($config->get('text_default_' . $lang->getId())['value'])
      != true ? $config->get('text_default_' . $lang->getId())['value'] : '';
      
      $form['langue_' . $lang->getId()]['privacy_policy_' . $lang->getId()]['text_default_' . $lang->getId()] = [
        '#type'           => 'text_format',
        '#title'          => $this->t('Text Privacy Policy'),
        '#description'    => $this->t('Privacy Policy Default Text'),
        '#format'        => $d_value,
        '#default_value'  => $d_format,
      ];
    }
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    foreach ($values as $configKey => $value) {
      $this->configFactory->getEditable(self::PARDOT_SETTINGS_KEY)
        ->set($configKey, $value)
        ->save();
    }
    parent::submitForm($form, $form_state);
  }
}
