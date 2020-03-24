<?php

namespace Drupal\soc_wishlist\Service\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_content_list\Service\Manager\ContentListManager;
use Drupal\soc_wishlist\Model\Wishlist;

class WishlistManager extends ContentListManager {

  /**
   * WishlistManager constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $contentList = new Wishlist();
    $cookieName = 'socomec_wishlist';
    $settings = $configFactory->getEditable('soc_wishlist.settings');
    $bundle = 'reference';
    $referencedField = 'field_reference_extid';
    $cookieIdField = 'extid';
    parent::__construct($contentList, $cookieName, $settings, $bundle, $referencedField, $cookieIdField);
  }

}
