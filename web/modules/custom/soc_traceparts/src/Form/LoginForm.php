<?php

namespace Drupal\soc_traceparts\Form;


use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\PrependCommand;

class LoginForm extends TracepartsForm {

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
    return 'soc_traceparts_login';
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
      $this->t('Register'),
      'soc_traceparts.register_form',
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

    // Form wrapper for AJAX
    $form['wrapper_login'] = [
      '#type' => 'container',
      '#prefix' => '<div id="soc-traceparts-wrapper-login">',
      '#suffix' => '</div>'
    ];

    $form['wrapper_login']['register'] = [
      '#markup' => '<p class="col-xs-12 go-to-button left">' . $registrationLink->toString() . '</p>',
    ];
    $form['wrapper_login']['message'] = [
      '#markup' => '<div class="soc-traceparts-message"></div>',
    ];
    $form['wrapper_login']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email address'),
      '#default_value' => $this->currentUser->getEmail() ?? '',
      '#required' => TRUE,
    ];
    $form['wrapper_login']['part_number'] = [
      '#type' => 'hidden',
      '#value' => $part_number,
    ];
    $form['wrapper_login']['format_id'] = [
      '#type' => 'hidden',
      '#value' => $format_id,
    ];

    $form['wrapper_login']['actions'] = ['#type' => 'actions'];
    $form['wrapper_login']['actions']['submit'] = [
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
    // Login success: go to download.
    if ($this->tracepartsUser->checkLogin($values['email']) === TRUE) {
      if ($link = $this->getDownloadLink($values)) {
        //$response->addCommand(new InvokeCommand('.ui-dialog', 'addClass', array('thank-you')));
        $response->addCommand(new ReplaceCommand('.ui-dialog-title', $this->t('Thank you!')));
        $response->addCommand(new ReplaceCommand('.soc-traceparts-login', $link));
      }
    }
    // Login fail: go to registration.
    else {
      $message = $this->t('Your email address is not associated to a Traceparts account. 
      Please register in order to access to your download.');
      $getForm = \Drupal::formBuilder()
        ->getForm('Drupal\soc_traceparts\Form\RegisterForm', $values['part_number'], $values['format_id']);
      $response->addCommand(new ReplaceCommand('.ui-dialog-title', $this->t('Login to Traceparts')));
      $response->addCommand(new ReplaceCommand('.soc-traceparts-login', $getForm));
      $messenger = \Drupal::messenger();
      if (isset($message)) {
        $messenger->addMessage($message, 'error');
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
