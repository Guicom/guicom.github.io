<?php

namespace Drupal\soc_nextpage\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class PendingProductEvent.
 *
 * @package Drupal\soc_nextpage\Event
 */
class PendingProductEvent extends Event {
  /**
   * The related pending product.
   */
  protected $pendingProduct;
  
  /**
   * Constructs a new $pendingProduct.
   *
   * @param $pendingProduct
   */
  public function __construct($pendingProduct) {
    $this->pendingProduct = $pendingProduct;
  }

  /**
   * Returns the original pending product.
   *
   * @return PendipendingProduct.
   */
  public function getPendingProduct() {

    return $this->pendingProduct;
  }
}
