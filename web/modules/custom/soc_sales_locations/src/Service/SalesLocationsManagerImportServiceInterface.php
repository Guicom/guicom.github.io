<?php

namespace Drupal\soc_sales_locations\Service;

use Drupal\Core\Database\Database;
use Drupal\Core\Database\Transaction;
use Drupal\file\FileInterface;

/**
 * Interface SalesLocationsManagerImportServiceInterface.
 */
interface SalesLocationsManagerImportServiceInterface {


  /**
   * @param \Drupal\file\FileInterface $file
   *
   * @return bool
   */
  public function validate(FileInterface $file) ;

  /**
   * @param array $row
   *
   * @param $token
   * @param \Drupal\Core\Database\Database $database
   *
   * @return bool
   */
  public function importRow($row, $token);

  /**
   * @param string $job_id
   *   Job Id.
   *
   * @return bool
   */
  public function updateCurrentJob($job_id);

}
