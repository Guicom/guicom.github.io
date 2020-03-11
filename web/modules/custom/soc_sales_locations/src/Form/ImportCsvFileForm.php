<?php

namespace Drupal\soc_sales_locations\Form;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dgddi_media_migrate\Batch\MigrateMedia;
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
  private $smi;

  public function __construct(SalesLocationsManagerImportService $smi) {
    $this->smi = $smi;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('soc_sales_locations.manager.import'));
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
      '#title' => $this->t('File CSV'),
      '#weight' => '0',
      '#upload_location' => 'private://csv_files',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
        'file_validate_size' => [25600000],
      ],
      '#description' => $this->t('Only CSV file.'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $file_id = $form_state->getValue('file_csv');
    // @todo: avoir une injection de dépendance dans les régles de l'art.
    /** @var \Drupal\file\Entity\File $file */
    $file = \Drupal::service('entity_type.manager')->getStorage('file')->load($file_id[0]);
   // @todo: avoir une injection de dépendance dans les régles de l'art.
    /** @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerImportServiceInterface $smi */
    $smi  = \Drupal::service('soc_sales_locations.manager.import');
    try {
      $smi->validate($file);

    } catch (Exception $e){
      \Drupal::messenger()->addError($e->getMessage());
    }
//    $queue = \Drupal::queue('location_import');
//    $queue->createQueue();
//
//    $operations = [];
//    $fh = fopen($file->getFileUri(), 'r');
//    $i = 0;
//    while ($row = fgetcsv($fh, 0, ';')) {
//      if ($i !== 0) {
//        $queue->createItem($row);
//      }
//      $i++;
//    }

    $batch = LocationsImportBatch::locationImport($file);
    batch_set($batch);

  }

}
