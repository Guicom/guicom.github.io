<?php

namespace Drupal\soc_nextpage\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\prae_pending_users\Form\PendingUsersMappingForm;
use Drupal\soc_nextpage\NextpageApiInterface;
use Drupal\soc_nextpage\Service\NextpageApi;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NextPageSynchroForm extends FormBase {

  /**
   * @var NextpageApi $nextpageApi
   */
  protected $nextpageApi;

  /**
   * Class constructor.
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
    // Date form synchro
    $form['date'] = [
      '#type' => 'date',
      '#title' => 'Import product from',
      '#description' => $this->t('Synchronize product from date'),
      '#disabled' => TRUE,
    ];

    // Batch size
    $form['batch_size'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Batch Size'),
      '#description' => $this->t('How many product will be process during batch process.'),
      '#default_value' => '50',
      '#size' => 4,
    ];

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
    $batch_size = $form_state->getValue('batch_size');
    $product = $this->nextpageApi->descendantsAndLinks([$form_state->getValue('ext_id')]);
    $test = 1;

    foreach ($product->Elements ?? [] as $row) {
      $operations[] = [
        '\Drupal\soc_nextpage\Batch\ImportPendingProduct::addPendingProduct',
        [$row]
      ];
    }

    // Setup batch.
    $batch = [
      'title' => t('Importing pending product...'),
      'operations' => $operations,
      'init_message' => t('Import is starting.'),
      'finished' => '\Drupal\coc_nextpage\Batch\ImportPendingProduct::addPendingProductCallback',
    ];
    batch_set($batch);


    $this->nextpageApi->synchroniseCharacteristicsDictionary("2");

    //$this->messenger()->addMessage($token, 'error');$this->logger('my_channel')->info($token);
    //
  }
}
