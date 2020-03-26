<?php

namespace Drupal\soc_sales_locations\Service;

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
   * @return bool
   */
  public function importRow($row);

  /**
   * @param \Drupal\file\FileInterface $file
   *
   * @return mixed
   */
  public function importAllRow(FileInterface $file);

}
