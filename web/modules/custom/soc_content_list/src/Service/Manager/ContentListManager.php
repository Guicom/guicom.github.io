<?php

namespace Drupal\soc_content_list\Service\Manager;

use Drupal\Core\Config\Config;
use Drupal\node\Entity\Node;
use Drupal\soc_content_list\Model\ContentList;

class ContentListManager {

  /** @var \Drupal\soc_content_list\Model\ContentList $contentList */
  protected $contentList;

  /** @var $cookie_name */
  protected $cookie_name;

  /** @var $settings */
  protected $settings;

  /** @var $bundle */
  protected $bundle;

  /** @var $referencedField */
  protected $referencedField;

  /**
   * WishlistManager constructor.
   *
   * @param \Drupal\soc_content_list\Model\ContentList $content_list
   * @param string $cookie_name
   * @param \Drupal\Core\Config\Config $settings
   * @param string $bundle
   * @param string $referenced_field
   */
  public function __construct(ContentList $content_list, string $cookie_name, Config $settings, string $bundle, string $referenced_field) {
    $this->contentList = $content_list;
    $this->cookie_name = $cookie_name;
    $this->settings = $settings;
    $this->bundle = $bundle;
    $this->referencedField = $referenced_field;
  }

  /**
   * Add an item.
   *
   * @param $extid
   *
   * @return bool
   */
  public function add($extid):bool {
    $items = $this->contentList->getItems();
    if (!is_array($items) || !array_key_exists($extid, $items)) {
      $items[$extid] = [
        'extid' => $extid,
        'quantity' => 1,
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
  public function remove($extid):bool {
    $items = $this->contentList->getItems();
    if (array_key_exists($extid, $items)) {
      unset($items[$extid]);
      $this->contentList->setItems($items);
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
      $items = $this->contentList->getItems();
      if (array_key_exists($extid, $items)) {
        $items[$extid]['quantity'] = $quantity;
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
          $preparedItems[$datas[$item->get($this->getReferencedField())->value]['extid']] =  [
            'node' => $item,
            'extid' => $datas[$item->get($this->getReferencedField())->value]['extid'],
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
    return $this->cookie_name;
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

}
