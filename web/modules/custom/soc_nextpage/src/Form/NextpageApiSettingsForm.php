<?php

namespace Drupal\soc_nextpage\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Link;
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
      '#type'           => 'details',
      '#open'           => TRUE,
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
      '#type'           => 'details',
      '#open'           => TRUE,
      '#title'          => $this->t('Authentication'),
    ];

    $active = array(0 => t('No'), 1 => t('Yes'));
    $form['auth']['auth_status'] = [
      '#type'           => 'radios',
      '#title'          => $this->t('Active auth connexion'),
      '#options' => $active,
      '#description'    => $this->t('Activate authentification.'),
      '#default_value'  => $config->get('auth_status') ??
        Settings::get('auth_status', 0),
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

    $form['context'] = [
      '#type'           => 'details',
      '#open'           => TRUE,
      '#title'          => $this->t('Settings'),
    ];

    $form['context']['channel_extid'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Channel ExtID'),
      '#description'    => $this->t('The channel extID for this site.'),
      '#default_value'  => $config->get('channel_extid') ?? '',
    ];

    $form['context']['context_id'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Context ID'),
      '#description'    => $this->t('The context ID to use to request nextPage.'),
      '#default_value'  => $config->get('context_id') ?? '1',
    ];

    $form['context']['langue'] = [
      '#type'           => 'details',
      '#open'           => FALSE,
      '#title'          => $this->t('Languages'),
    ];

    // Get enable language
    $langueManager = \Drupal::languageManager();
    $langcodes = $langueManager->getLanguages();
    $langcodes = array_keys($langcodes);

    // Add one field for one language
    foreach ($langcodes as $lang) {
      $form['context']['langue']['language_id_' . $lang] = [
        '#type'           => 'textfield',
        '#title'          => $this->t('Language ID for @language (@langid)', [
                              '@language' => $langueManager->getLanguageName($lang),
                              '@langid' => $lang
                              ]),
        '#description'    => $this->t('The language ID to use to request nextPage.'),
        '#default_value'  => $config->get('language_id_' . $lang) ?? '1',
      ];
    }

    $form['endpoints'] = [
      '#type'           => 'details',
      '#open'           => TRUE,
      '#title'          => $this->t('Endpoints'),
    ];

    $form['endpoints']['endpoint_token'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Auth'),
      '#description'    => $this->t('Get a token.') . ' ' . Link::createFromRoute('Test',
          'soc_nextpage.test_get_token', [], [
            'attributes' => [
              'target' => '_blank',
            ],
          ]
          )->toString(),
      '#default_value'  => $config->get('endpoint_token') ?? 'api/auth',
    ];

    $form['endpoints']['endpoint_dicocarac'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('GetAll'),
      '#description'    => $this->t('Get the characteristics dictionary.') . ' ' . Link::createFromRoute('Test',
          'soc_nextpage.test_characteristics', [], [
            'attributes' => [
              'target' => '_blank',
            ],
          ]
        )->toString(),
      '#default_value'  => $config->get('endpoint_dicocarac') ?? 'api/sdk-debug/dicocarac/GetAll',
    ];

    $form['endpoints']['endpoint_elementsandlinks'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('ElementsAndLinks'),
      '#description'    => $this->t('Get an element and its characteristics.') . ' ' . Link::createFromRoute('Test',
          'soc_nextpage.test_element', [], [
            'attributes' => [
              'target' => '_blank',
            ],
          ]
        )->toString(),
      '#default_value'  => $config->get('endpoint_elementsandlinks') ?? 'api/sdk-ext/element/ElementsAndLinks',
    ];

    $form['endpoints']['endpoint_descendantsandlinks'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('DescendantsAndLinks'),
      '#description'    => $this->t('Get an hierarchy.') . ' ' . Link::createFromRoute('Test',
          'soc_nextpage.test_descendants', [], [
            'attributes' => [
              'target' => '_blank',
            ],
          ]
        )->toString(),
      '#default_value'  => $config->get('endpoint_descendantsandlinks') ?? 'api/sdk-ext/element/DescendantsAndLinks',
    ];

    $form['endpoints']['endpoint_elementsbychartemplate'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('ElementsByCharTemplate'),
      '#description'    => $this->t('Get elements by product type.') . ' ' . Link::createFromRoute('Test',
          'soc_nextpage.test_elements_by_char_template', [], [
            'attributes' => [
              'target' => '_blank',
            ],
          ]
        )->toString(),
      '#default_value'  => $config->get('endpoint_elementsbychartemplate') ?? 'api/sdk-ext/element/ElementsByCharTemplate',
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
               'password',
               'context_id',
               'channel_extid',
               'endpoint_token',
               'endpoint_dicocarac',
               'endpoint_elementsandlinks',
               'endpoint_descendantsandlinks',
               'endpoint_elementsbychartemplate',] as $configKey) {
      $this->configFactory->getEditable(self::WS_SETTINGS_KEY)
        ->set($configKey, $form_state->getValue($configKey))
        ->save();
    }
    // Get enable language
    $langueManager = \Drupal::languageManager();
    $langcodes = $langueManager->getLanguages();
    $langcodes = array_keys($langcodes);

    foreach ($langcodes as $lang) {
      $key = 'language_id_' . $lang;
      $this->configFactory->getEditable(self::WS_SETTINGS_KEY)
        ->set($key, $form_state->getValue($key))
        ->save();
    }

    parent::submitForm($form, $form_state);
  }
}
