<?php

namespace Drupal\soc_wishlist\Service\Manager;

use Drupal\soc_wishlist\Model\Wishlist;

class WishlistManager {

  /** @var $wishlist */
  protected $wishlist;

  public function __construct() {
    $this->wishlist = new Wishlist();
  }

  /**
   * @param $extid
   *
   * @return bool
   */
  public function add($extid):bool {
    if (!array_search($extid, $this->wishlist->getItems())) {
      $this->wishlist[$extid] = [
        'extid' => $extid,
        'quantity' => 1,
      ];
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @param $extid
   *
   * @return bool
   */
  public function addOne($extid):bool {
    $items = $this->wishlist->getItems();
    if (array_search($extid, $items)) {
      $items[$extid]['quantity'] += 1;
      $this->wishlist->setItems($items);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @param $extid
   *
   * @return bool
   */
  public function remove($extid):bool {
    if (array_search($extid, $this->wishlist->getItems())) {
      unset($this->wishlist[$extid]);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @param $extid
   *
   * @return bool
   */
  public function removeOne($extid):bool {
    $items = $this->wishlist->getItems();
    if (array_search($extid, $items)) {
      $items[$extid]['quantity'] -= 1;
      $this->wishlist->setItems($items);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @param $extid
   * @param $quantity
   *
   * @return bool
   */
  public function setQuantity($extid, $quantity):bool {
    if (is_int($quantity) === TRUE) {
      $items = $this->wishlist->getItems();
      if (array_search($extid, $items)) {
        $items[$extid]['quantity'] = $quantity;
        $this->wishlist->setItems($items);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @return array
   */
  public function getAll():array {
    return $this->wishlist->getItems();
  }

}
