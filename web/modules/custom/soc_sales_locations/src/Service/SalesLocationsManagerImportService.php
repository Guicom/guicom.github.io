<?php

namespace Drupal\soc_sales_locations\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;

/**
 * Class SalesLocationsManagerImportService.
 */
class SalesLocationsManagerImportService implements SalesLocationsManagerImportServiceInterface {

  /**
   * Drupal\Core\Entity\EntityManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * Drupal\Core\File\FileSystemInterface definition.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructs a new SalesLocationsManagerImportService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   */
  public function __construct(EntityTypeManagerInterface $entity_manager, FileSystemInterface $file_system) {
    $this->entityManager = $entity_manager;
    $this->fileSystem = $file_system;
  }

  /**
   * @inheritDoc
   */
  public function validate(FileInterface $file) {
    $fh = fopen($file->getFileUri(), 'r');
    $row = fgetcsv($fh, 0, ";");
    if (empty($row) || count($row) !== 21) {
      return FALSE;
    }
    return TRUE;
  }


  /**
   * @inheritDoc
   */
  public function importRow($row) {
    dsm($row);
  }

  /**
   * @inheritDoc
   */
  public function importAllRow(FileInterface $file) {
    $fh = fopen($file->getFileUri(), 'r');
    while ($row = fgetcsv($fh, 0, ';')) {
      $this->importRow($row);
    }
  }

}
