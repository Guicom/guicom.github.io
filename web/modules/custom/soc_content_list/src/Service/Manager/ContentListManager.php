<?php

namespace Drupal\soc_content_list\Service\Manager;

use Drupal\Core\Config\Config;
use Drupal\node\Entity\Node;
use Drupal\soc_content_list\Model\ContentList;

class ContentListManager {

  /** @var \Drupal\soc_content_list\Model\ContentList $contentList */
  protected $contentList;

  /** @var $cookieName */
  protected $cookieName;

  /** @var $settings */
  protected $settings;

  /** @var $bundle */
  protected $bundle;

  /** @var $referencedField */
  protected $referencedField;
  
  /** @var $cookieIdField */
  protected $cookieIdField;

  /** @var $ItemActionRoute */
  protected $itemActionRoute;

  /** @var $lastDeletedSessionName */
  protected $lastDeletedSessionName;

  /**
   * WishlistManager constructor.
   *
   * @param \Drupal\soc_content_list\Model\ContentList $content_list
   * @param string $cookie_name
   * @param \Drupal\Core\Config\Config $settings
   * @param string $bundle
   * @param string $referenced_field
   */
  public function __construct(ContentList $content_list, string $cookie_name,
                              Config $settings, string $bundle, string $referenced_field,
                              string $cookieIdField, string $itemActionRoute, string $lastDeletedSessionName) {
    $this->contentList = $content_list;
    $this->cookieName = $cookie_name;
    $this->settings = $settings;
    $this->bundle = $bundle;
    $this->referencedField = $referenced_field;
    $this->cookieIdField = $cookieIdField;
    $this->itemActionRoute = $itemActionRoute;
    $this->lastDeletedSessionName = $lastDeletedSessionName;
  }

  /**
   * Add an item.
   *
   * @param $extid
   *
   * @return bool
   */
  public function add($itemId):bool {
    $items = $this->contentList->getItems();
    if (!is_array($items) || !array_key_exists($itemId, $items)) {
      $items[$itemId] = [
        $this->getCookieIdField() => $itemId,
        'quantity' => 1,
        'timestamp' => time(),
      ];
      $this->contentList->setItems($items);
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
  public function remove($itemId):bool {
    $items = $this->contentList->getItems();
    if (array_key_exists($itemId, $items)) {
      unset($items[$itemId]);
      $this->contentList->setItems($items);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Update quantity of an item.
   *
   * @param $itemId
   * @param $quantity
   *
   * @return bool
   */
  public function setQuantity($itemId, $quantity):bool {
    if (is_numeric($quantity) === TRUE) {
      $items = $this->contentList->getItems();
      if (array_key_exists($itemId, $items)) {
        $items[$itemId]['quantity'] = $quantity;
        $this->contentList->setItems($items);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Load content list from cookie.
   *
   * @return array
   */
  public function loadSavedItems() {
    if (isset($_COOKIE[$this->getCookieName()])) {
      $contentList = $_COOKIE[$this->getCookieName()];
      $this->contentList->setItems(json_decode($contentList, TRUE));
      // Load items.
      $datas = $this->contentList->getItems();
      if (!empty($datas)) {
        $itemsQuery = \Drupal::entityQuery('node');
        $itemsQuery->condition('type', $this->getBundle());
        $itemsQuery->condition($this->getReferencedField(), array_keys($datas), 'IN');
        $itemsResults = $itemsQuery->execute();
        $items = Node::loadMultiple($itemsResults);
        $preparedItems = [];
        foreach ($items as $item) {
          $preparedItems[$datas[$item->get($this->getReferencedField())->value][$this->getCookieIdField()]] =  [
            'node' => $item,
            $this->getCookieIdField() => $datas[$item->get($this->getReferencedField())->value][$this->getCookieIdField()],
            'quantity' => $datas[$item->get($this->getReferencedField())->value]['quantity']
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
   * Update content list cookie.
   *
   * @throws \Exception
   */
  public function updateCookie() {
    $name = $this->getCookieName();
    $value = json_encode($this->contentList->getItems());
    $expireDays = $this->settings->get('cookie_lifetime_days');
    $expire = time() + (3600 * 24 * $expireDays); // now + X days
    $path = '/';
    $domain = \Drupal::request()->getHost();
    if (!setcookie($name, $value, $expire, $path)) {
      throw new \Exception('Unable to save the list.
      Please check that your browser settings are allowing cookies, then try again.');
    }
  }

  /**
   * @return mixed
   */
  public function getCookieName() {
    return $this->cookieName;
  }

  /**
   * @return mixed
   */
  public function getBundle() {
    return $this->bundle;
  }

  /**
   * @return mixed
   */
  public function getReferencedField() {
    return $this->referencedField;
  }

  /**
   * @return mixed
   */
  public function getCookieIdField() {
    return $this->cookieIdField;
  }

  /**
   * @return mixed
   */
  public function getItemActionRoute() {
    return $this->itemActionRoute;
  }

  /**
   * @return mixed
   */
  public function getlastDeletedSessionName() {
    return $this->lastDeletedSessionName;
  }


}
