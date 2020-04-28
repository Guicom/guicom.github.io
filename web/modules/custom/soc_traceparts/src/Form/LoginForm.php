<?php

namespace Drupal\soc_traceparts\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Session\AccountInterface;
use Drupal\soc_traceparts\Service\Manager\TracepartsUserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoginForm extends FormBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /** @var TracepartsUserManager $tracepartsUser */
  protected $tracepartsUser;

  /**
   * Constructs an AnonymousUserResponseSubscriber object.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\soc_traceparts\Service\Manager\TracepartsUserManager $traceparts_user
   */
  public function __construct(AccountInterface $current_user,
                              TracepartsUserManager $traceparts_user) {
    $this->currentUser = $current_user;
    $this->tracepartsUser = $traceparts_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('soc_traceparts.traceparts_user_manager')
    );
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
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $registrationLink = Link::createFromRoute(
      $this->t('Register'),
      'soc_traceparts.register_form'
    );

    $form['register'] = [
      '#markup' => '<p class="col-xs-12">' . $registrationLink->toString() . '</p>',
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email address'),
      '#default_value' => $this->currentUser->getEmail() ?? '',
      '#required' => TRUE,
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
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();
    var_dump($this->tracepartsUser->checkLogin($values['email']));
  }
}
