<?php

namespace Drupal\soc_nextpage\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\prae_pending_users\Form\PendingUsersMappingForm;
use Drupal\soc_nextpage\Batch\ImportPendingElement;
use Drupal\soc_nextpage\NextpageApiInterface;
use Drupal\soc_nextpage\Service\Manager\ProductManager;
use Drupal\soc_nextpage\Service\NextpageApi;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NextPageSynchroForm extends FormBase {

  /**
   * @var NextpageApi $nextpageApi
   */
  protected $nextpageApi;

  /**
   * @var \Drupal\soc_nextpage\Service\Manager\ProductManager
   */
  private $productManager;

  /**
   * Class constructor.
   *
   * @param \Drupal\soc_nextpage\Service\NextpageApi $nextpageApi
   */
  public function __construct(NextpageApi $nextpageApi) {
    $this->nextpageApi = $nextpageApi;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $container->get('soc_nextpage.nextpage_api')
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
    return 'next_page_synchro_form';
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
    // Synchro submit
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import PIM Product'),
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
    ImportPendingElement::buildBatch();
  }
}
