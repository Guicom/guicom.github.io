<?php

namespace Drupal\soc_wishlist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WishlistSettingsForm
 *
 * @package Drupal\soc_wishlist\Form
 */
class WishlistSettingsForm extends ConfigFormBase {

  const WS_SETTINGS_KEY = 'soc_wishlist.settings';

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
    $languages = $this->languageManager->getLanguages();

    foreach ($languages as $lang) {

      $form['language_' . $lang->getId()] = [
        '#type'           => 'details',
        '#open'           => TRUE,
        '#title'          => $lang->getName(),
      ];

      $form['language_' . $lang->getId()]['page_title_' . $lang->getId()] = [
        '#type'           => 'textfield',
        '#title'          => $this->t('Page title'),
        '#description'    => $this->t('The title of the wishlist page.'),
        '#default_value'  => $config->get('page_title_' . $lang->getId())
          ?? $this->t('My wishlist'),
      ];

      $form['language_' . $lang->getId()]['wishlist_no_result_' . $lang->getId()] = [
        '#type' => 'text_format',
        '#format' => 'socomec',
        '#title'          => $this->t('Message without result'),
        '#description'    => $this->t('The message when wishlist is empty.'),
        '#default_value'  => $config->get('wishlist_no_result_'. $lang->getId())
          ?? $this->t('You do not have any references saved.'),
      ];

    }

    $form['settings'] = [
      '#type'           => 'details',
      '#open'           => FALSE,
      '#title'          => $this->t('Settings'),
    ];

    $form['settings']['cookie_lifetime_days'] = [
      '#type'           => 'number',
      '#title'          => $this->t('Wishlist lifetime'),
      '#description'    => $this->t('The number of days the wishlist will be stored.'),
      '#default_value'  => $config->get('cookie_lifetime_days') ?? 60,
    ];

    $form['settings']['pdf_disclaimer'] = [
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
    $values = $form_state->getValues();
    foreach ($values as $configKey => $value) {
      $languages = $this->languageManager->getLanguages();
      if (strpos($configKey, 'wishlist_no_result_') !== FALSE) {
        foreach ($languages as $lang) {
          if ($configKey === 'wishlist_no_result_' . $lang->getId()) {
            $this->configFactory->getEditable(self::WS_SETTINGS_KEY)
              ->set($configKey, $value['value'])
              ->save();
          }
        }
      }
      else {
        $this->configFactory->getEditable(self::WS_SETTINGS_KEY)
          ->set($configKey, $value)
          ->save();
      }
    }
    parent::submitForm($form, $form_state);
  }
}
