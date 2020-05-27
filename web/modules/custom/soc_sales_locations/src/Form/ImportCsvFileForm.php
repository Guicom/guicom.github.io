<?php

namespace Drupal\soc_sales_locations\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\soc_sales_locations\Batch\LocationsImportBatch;
use Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ImportCsvFileForm.
 */
class ImportCsvFileForm extends FormBase {

  /**
   * @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService
   */
  protected $importManager;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  public function __construct(SalesLocationsManagerImportService $import_manager,
                              EntityTypeManagerInterface $entityTypeManager) {
    $this->importManager = $import_manager;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_sales_locations.manager.import'),
      $container->get('entity_type.manager')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'import_csv_file_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file_csv'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Import file'),
      '#weight' => '0',
      '#upload_location' => 'private://csv_files',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
        'file_validate_size' => [25600000],
      ],
      '#description' => $this->t('CSV format.'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import office locations'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fileId = $form_state->getValue('file_csv');
    try {
      $file = $this->entityTypeManager->getStorage('file')->load($fileId[0]);
      if ($this->importManager->validate($file)) {
        $batch = LocationsImportBatch::locationImport($file);
        batch_set($batch);
      }
    } catch (Exception $e){
      \Drupal::messenger()->addError($e->getMessage());
    }
  }

}
