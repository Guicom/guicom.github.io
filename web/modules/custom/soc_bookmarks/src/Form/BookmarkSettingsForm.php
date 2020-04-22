<?php

namespace Drupal\soc_bookmarks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BookmarkSettingsForm
 *
 * @package Drupal\soc_bookmarks\Form
 */
class BookmarkSettingsForm extends ConfigFormBase {

  const WS_SETTINGS_KEY = 'soc_bookmarks.settings';

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

    $form['bookmark_page_title'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Page title'),
      '#description'    => $this->t('The title of the Bookmark page.'),
      '#default_value'  => $config->get('bookmark_page_title')
        ?? $this->t('My documents'),
    ];

    $form['bookmark_no_result'] = [
      '#type' => 'text_format',
      '#format' => 'socomec',
      '#title'          => $this->t('Message without result'),
      '#description'    => $this->t('The message when bookmark is empty.'),
      '#default_value'  => $config->get('bookmark_no_result')
        ?? $this->t('No result'),
    ];

    $form['bookmark_settings'] = [
      '#type'           => 'details',
      '#open'           => FALSE,
      '#title'          => $this->t('Settings'),
    ];

    $form['bookmark_settings']['cookie_lifetime_days'] = [
      '#type'           => 'number',
      '#title'          => $this->t('Bookmark lifetime'),
      '#description'    => $this->t('The number of days the Bookmark will be stored.'),
      '#default_value'  => $config->get('cookie_lifetime_days') ?? 60,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $config = $this->configFactory->getEditable(self::WS_SETTINGS_KEY);
    foreach ($values as $configKey => $value) {
      if ($configKey === 'bookmark_no_result') {
        $config->set($configKey, $value['value']);
      }
      else {
        $config->set($configKey, $value);
      }
    }
    try {
    $config->save();
    } catch (\Exception $e) {
      \Drupal::logger('soc_bookmarks')->error($e->getMessage());
    }

    parent::submitForm($form, $form_state);
  }
}
