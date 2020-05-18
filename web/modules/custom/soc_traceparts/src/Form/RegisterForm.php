<?php

namespace Drupal\soc_traceparts\Form;


use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Locale\CountryManager;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\PrependCommand;

class RegisterForm extends TracepartsForm {

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
    return 'soc_traceparts_register';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @param null $part_number
   * @param null $format_id
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state,
                            $part_number = NULL, $format_id = NULL) {
    $registrationLink = Link::createFromRoute(
      $this->t('Login'),
      'soc_traceparts.login_form',
      [
        'part_number' => $part_number,
        'format_id' => $format_id,
      ],
      [
        'attributes' => [
          'class' => ['use-ajax'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode([
            'width' => 640,
            'minHeight' => 500,
          ]),
        ],
      ]
    );

    $values = $form_state->cleanValues()->getValues();
    // Form wrapper for AJAX
    $form['wrapper_register'] = [
      '#type' => 'container',
      '#prefix' => '<div id="soc-traceparts-wrapper-register">',
      '#suffix' => '</div>'
    ];
    $form['wrapper_register']['register'] = [
      '#markup' => '<p class="col-xs-12 go-to-button left">' . $registrationLink->toString() . '</p>',
    ];
    $form['wrapper_register']['message'] = [
      '#markup' => '<div class="soc-traceparts-message"></div>',
    ];
    $form['wrapper_register']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email address'),
      '#default_value' => $values['email'] ?? $this->currentUser->getEmail() ?? '',
      '#required' => TRUE,
    ];
    $form['wrapper_register']['company'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your company'),
      '#default_value' => $values['company'] ?? '',
      '#required' => TRUE,
    ];
    $form['wrapper_register']['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Your country'),
      '#options' => CountryManager::getStandardList(),
      '#default_value' => $values['country'] ?? '',
      '#required' => TRUE,
    ];
    $form['wrapper_register']['zipcode'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your zip code'),
      '#default_value' => $values['zipcode'] ?? '',
      '#required' => TRUE,
    ];
    $form['wrapper_register']['part_number'] = [
      '#type' => 'value',
      '#value' => $part_number,
    ];
    $form['wrapper_register']['format_id'] = [
      '#type' => 'value',
      '#value' => $format_id,
    ];

    $form['wrapper_register']['actions'] = ['#type' => 'actions'];
    $form['wrapper_register']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Continue'),
      '#attributes' => ['class' => ['use-ajax-submit']],
      '#ajax' => [
        'callback' => '::callTracePartCallback',
      ],
    ];


    return $form;
  }

  /**
   * Ajax callback
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function callTracePartCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $values = $form_state->cleanValues()->getValues();
    $userData = [
      'UserEmail' => $values['email'],
      'company' => $values['company'],
      'country' => $values['country'],
      'zipcode' => $values['zipcode'],
    ];
    // Account creation success: go to download.
    if ($this->tracepartsUser->registerUser($userData) === TRUE) {
      if ($link = $this->getDownloadLink($values)) {
        $response->addCommand(new ReplaceCommand('.ui-dialog-title', $this->t('Thank you!')));
        $response->addCommand(new ReplaceCommand('#soc-traceparts-wrapper-register', $link));
      }
    }
    // Account creation fail: display message.
    else {
      $message = $this->t('Your Traceparts account could not be created. Please try again in a few moments.');
      $messenger = \Drupal::messenger();
      if (isset($message)) {
        $messenger->addMessage($message, 'warning');
        $status_messages =  [
          '#type' => 'status_messages'
        ];
        $messages = \Drupal::service('renderer')->renderRoot($status_messages);
        if (!empty($messages)) {
          $response->addCommand(new PrependCommand('.soc-traceparts-message', $messages));
        }
      }
    }
    return $response;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
