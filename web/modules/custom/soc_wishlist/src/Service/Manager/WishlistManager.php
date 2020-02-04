<?php

namespace Drupal\soc_wishlist\Service\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_wishlist\Model\Wishlist;

class WishlistManager {

  /** @var $wishlist */
  protected $wishlist;

  /** @var $cookie_name */
  protected $cookie_name;

  /** @var $settings */
  protected $settings;

  /**
   * WishlistManager constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->wishlist = new Wishlist();
    $this->cookie_name = 'socomec_wishlist';
    $this->settings = $configFactory->getEditable('soc_wishlist.settings');
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
    if (is_numeric($quantity) === TRUE) {
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
      // Load items.
      $datas = $this->wishlist->getItems();
      if (!empty($datas)) {
        $itemsQuery = \Drupal::entityQuery('node');
        $itemsQuery->condition('type', 'product_reference');
        $itemsQuery->condition('field_reference_extid', array_keys($datas), 'IN');
        $itemsResults = $itemsQuery->execute();
        $items = Node::loadMultiple($itemsResults);
        $preparedItems = [];
        foreach ($items as $item) {
          $preparedItems[$datas[$item->get('field_reference_extid')->value]['extid']] =  [
            'node' => $item,
            'extid' => $datas[$item->get('field_reference_extid')->value]['extid'],
            'quantity' => $datas[$item->get('field_reference_extid')->value]['quantity']
          ];
        }
      }
      if (!empty($preparedItems)) {
        return $preparedItems;
      }
    }
    return [];
  }

  /**
   * Update wishlist cookie.
   *
   * @throws \Exception
   */
  public function updateCookie() {
    $name = $this->getCookieName();
    $value = json_encode($this->wishlist->getItems());
    $expireDays = $this->settings->get('cookie_lifetime_days');
    $expire = time() + (3600 * 24 * $expireDays); // now + X days
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
