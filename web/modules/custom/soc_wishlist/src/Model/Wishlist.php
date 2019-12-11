<?php

namespace Drupal\soc_wishlist\Model;

class Wishlist {

  /** @var $items */
  protected $items;

  /**
   * @return mixed
   */
  public function getItems():array {
    return $this->items ?? [];
  }

  /**
   * @param mixed $items
   */
  public function setItems($items): void {
    $this->items = $items;
  }

}
