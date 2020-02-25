<?php

namespace Drupal\soc_search\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Provides settings for soc_search module.
 */
class SearchSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'soc_search_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'soc_search.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $settings  = $this->config('soc_search.settings');

    $form['soc_search'] = [
      '#type'  => 'details',
      '#open'  => TRUE,
      '#title' => $this->t('Search - settings'),
    ];

    $form['soc_search']['title'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $settings->get('title'),
    ];

    $form['soc_search']['placeholder'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Placeholder'),
      '#default_value' => $settings->get('placeholder'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save settings.
    $settings = $this->configFactory->getEditable('soc_search.settings');
    $settings->set('title', $form_state->getValue('title'))->save();
    $settings->set('placeholder', $form_state->getValue('placeholder'))->save();
  }

}
