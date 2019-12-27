<?php

namespace Drupal\soc_wishlist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class WishlistSettingsForm
 *
 * @package Drupal\soc_wishlist\Form
 */
class WishlistSettingsForm extends ConfigFormBase {

  const WS_SETTINGS_KEY = 'soc_wishlist.settings';

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
      '#type'           => 'details',
      '#open'           => TRUE,
      '#title'          => $this->t('Global'),
    ];

    $form['global']['cookie_lifetime_days'] = [
      '#type'           => 'number',
      '#title'          => $this->t('Wishlist lifetime'),
      '#description'    => $this->t('The number of days the wishlist will be stored.'),
      '#default_value'  => $config->get('cookie_lifetime_days') ?? 60,
    ];

    $form['export'] = [
      '#type'           => 'details',
      '#open'           => TRUE,
      '#title'          => $this->t('Export settings'),
    ];

    $form['export']['pdf_disclaimer'] = [
      '#type'           => 'textarea',
      '#title'          => $this->t('PDF disclaimer'),
      '#description'    => $this->t('The disclaimer will be added on the PDF exports.'),
      '#default_value'  => $config->get('pdf_disclaimer') ?? 'This is the PDF disclaimer text.',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ([
               'cookie_lifetime_days',
               'pdf_disclaimer',] as $configKey) {
      $this->configFactory->getEditable(self::WS_SETTINGS_KEY)
        ->set($configKey, $form_state->getValue($configKey))
        ->save();
    }

    parent::submitForm($form, $form_state);
  }
}
