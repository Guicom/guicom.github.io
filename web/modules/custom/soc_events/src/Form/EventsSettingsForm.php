<?php

namespace Drupal\soc_events\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EventsSettingsForm.
 */
class EventsSettingsForm extends ConfigFormBase {

  /*
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
    return new static($container->get('language_manager'));
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'soc_events.eventssettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'events_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('soc_events.eventssettings');


    $languages = $this->languageManager->getLanguages();

    foreach ($languages as $lang) {

      $form['language_' . $lang->getId()] = [
        '#type' => 'details',
        '#open' => TRUE,
        '#title' => $lang->getName(),
      ];

      $form['language_' . $lang->getId()]['page_title_' . $lang->getId()] = [
        '#type' => 'textfield',
        '#title' => $this->t('Page title'),
        '#description' => $this->t('The title of the events page.'),
        '#default_value' => $config->get('page_title_' . $lang->getId()) ?? $this->t('Events'),
      ];

    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    foreach ($values as $configKey => $value) {
      $this->configFactory->getEditable('soc_events.eventssettings')
        ->set($configKey, $value)
        ->save();
    }
    parent::submitForm($form, $form_state);
  }

}
