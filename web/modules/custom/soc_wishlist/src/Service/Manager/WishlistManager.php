<?php

namespace Drupal\soc_wishlist\Service\Manager;

use Drupal\soc_wishlist\Model\Wishlist;

class WishlistManager {

  /** @var $wishlist */
  protected $wishlist;

  /** @var $cookie_name */
  protected $cookie_name;

  /**
   * WishlistManager constructor.
   */
  public function __construct() {
    $this->wishlist = new Wishlist();
    $this->cookie_name = 'socomec_wishlist';
  }

  /**
   * Add an item.
   *
   * @param $extid
   *
   * @return bool
   */
  public function add($extid):bool {
    $items = $this->wishlist->getItems();
    if (!is_array($items) || !array_key_exists($extid, $items)) {
      $items[$extid] = [
        'extid' => $extid,
        'quantity' => 1,
      ];
      $this->wishlist->setItems($items);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Increase quantity of an item.
   *
   * @param $extid
   *
   * @return bool
   */
  public function addOne($extid):bool {
    $items = $this->wishlist->getItems();
    if (array_key_exists($extid, $items)) {
      $items[$extid]['quantity'] += 1;
      $this->wishlist->setItems($items);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Remove an item.
   *
   * @param $extid
   *
   * @return bool
   */
  public function remove($extid):bool {
    $items = $this->wishlist->getItems();
    if (array_key_exists($extid, $items)) {
      unset($items[$extid]);
      $this->wishlist->setItems($items);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Decrease quantity of an item.
   *
   * @param $extid
   *
   * @return bool
   */
  public function removeOne($extid):bool {
    $items = $this->wishlist->getItems();
    if (array_key_exists($extid, $items)) {
      $items[$extid]['quantity'] -= 1;
      if ($items[$extid]['quantity'] < 1) {
        unset($items[$extid]);
      }
      $this->wishlist->setItems($items);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Update quantity of an item.
   *
   * @param $extid
   * @param $quantity
   *
   * @return bool
   */
  public function setQuantity($extid, $quantity):bool {
    if (is_int($quantity) === TRUE) {
      $items = $this->wishlist->getItems();
      if (array_key_exists($extid, $items)) {
        $items[$extid]['quantity'] = $quantity;
        $this->wishlist->setItems($items);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Load wishlist from cookie.
   *
   * @return array
   */
  public function loadSavedItems() {
    if (isset($_COOKIE[$this->getCookieName()])) {
      $wishlist = $_COOKIE[$this->getCookieName()];
      $this->wishlist->setItems(json_decode($wishlist, TRUE));
      return $this->wishlist->getItems();
    }
    return [];
  }

  /**
   * Update wishlist cookie.
   */
  public function updateCookie() {
    $name = $this->getCookieName();
    $value = json_encode($this->wishlist->getItems());
    $expire = time() + (3600 * 24 * 60); // now + 60 days
    $path = '/';
    $domain = \Drupal::request()->getHost();
    if (!setcookie($name, $value, $expire, $path, $domain)) {
      throw new \Exception('Unable to save the wishlist.
      Please check that your browser settings are allowing cookies, then try again.');
    }
  }

  /**
   * @return mixed
   */
  public function getCookieName() {
    return $this->cookie_name;
  }

}
