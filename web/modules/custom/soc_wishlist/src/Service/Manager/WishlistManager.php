<?php

namespace Drupal\soc_wishlist\Service\Manager;

use Drupal\soc_wishlist\Model\Wishlist;

class WishlistManager {

  /** @var $wishlist */
  protected $wishlist;

  /**
   * @param $extid
   *
   * @return bool
   */
  public function add($extid):bool {}

  /**
   * @param $extid
   *
   * @return bool
   */
  public function addOne($extid):bool {}

  /**
   * @param $extid
   *
   * @return bool
   */
  public function remove($extid):bool {}

  /**
   * @param $extid
   *
   * @return bool
   */
  public function removeOne($extid):bool {}

  /**
   * @param $extid
   * @param $quantity
   *
   * @return bool
   */
  public function setQuantity($extid, $quantity):bool {}

  /**
   * @return \Drupal\soc_wishlist\Model\Wishlist
   */
  public function getAll():Wishlist {}

}
