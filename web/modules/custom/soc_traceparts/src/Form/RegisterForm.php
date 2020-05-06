<?php

namespace Drupal\soc_traceparts\Form;


use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Locale\CountryManager;

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
          'class=' => 'use-ajax',
          'data-dialog-type' => 'modal',
        ],
      ]
    );

    $values = $form_state->cleanValues()->getValues();
    $form['register'] = [
      '#markup' => '<p class="col-xs-12">' . $registrationLink->toString() . '</p>',
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email address'),
      '#default_value' => $values['email'] ?? $this->currentUser->getEmail() ?? '',
      '#required' => TRUE,
    ];
    $form['company'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your company'),
      '#default_value' => $values['company'] ?? '',
      '#required' => TRUE,
    ];
    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Your country'),
      '#options' => CountryManager::getStandardList(),
      '#default_value' => $values['country'] ?? '',
      '#required' => TRUE,
    ];
    $form['zipcode'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your zip code'),
      '#default_value' => $values['zipcode'] ?? '',
      '#required' => TRUE,
    ];
    $form['part_number'] = [
      '#type' => 'value',
      '#value' => $part_number,
    ];
    $form['format_id'] = [
      '#type' => 'value',
      '#value' => $format_id,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Continue'),
    ];


    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return void
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();
    $userData = [
      'UserEmail' => $values['email'],
      'company' => $values['company'],
      'country' => $values['country'],
      'zipcode' => $values['zipcode'],
    ];
    // Account creation success: go to download.
    if ($this->tracepartsUser->registerUser($userData) === TRUE) {
      $tempStore = $this->tempStoreFactory->get('soc_traceparts_user_data');
      try {
        $tempStore->set('email', $values['email']);
        $tempStore->set('part_number', $values['part_number']);
        $tempStore->set('format_id', $values['format_id']);
        $form_state->setRedirect('soc_traceparts.download');
      }
      catch (\Exception $e) {
        $this->logger('soc_traceparts')->alert($e->getMessage());
      }
    }
    // Account creation fail: display message.
    else {
      $message = $this->t('Your Traceparts account could not be created. Please try again in a few moments.');
      \Drupal::messenger()->addMessage($message);
    }
    return;
  }
}
