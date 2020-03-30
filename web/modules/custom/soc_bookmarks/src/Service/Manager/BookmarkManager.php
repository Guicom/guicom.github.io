<?php

namespace Drupal\soc_bookmarks\Service\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_content_list\Service\Manager\ContentListManager;
use Drupal\soc_bookmarks\Model\Bookmark;

class BookmarkManager extends ContentListManager {

  /**
   * BookmarkManager constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $contentList = new Bookmark();
    $cookieName = 'socomec_bookmark';
    $settings = $configFactory->getEditable('soc_bookmarks.settings');
    $bundle = 'resource';
    $referencedField = 'nid';
    $cookieIdField = 'nid';
    $ItemActionRoute = 'soc_bookmarks.edit_bookmark';
    $LastDeletedName = 'socomec_bookmark_last_deleted';
    parent::__construct($contentList, $cookieName, $settings, $bundle,
      $referencedField, $cookieIdField, $ItemActionRoute, $LastDeletedName);
  }

}
