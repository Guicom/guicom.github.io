<?php

namespace Drupal\soc_sales_locations\Service;

use Drupal\Core\Entity\EntityInterface;

/**
 * Interface SalesLocationsManagerImportServiceInterface.
 */
interface SalesLocationsManagerImportServiceInterface {


  /**
   * @param \Drupal\Core\Entity\EntityInterface $file
   *
   * @return bool
   */
  public function validate(EntityInterface $file) ;

  /**
   * @param array $row
   *
   * @param $token
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
