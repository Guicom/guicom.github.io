<?php


namespace Drupal\soc_content\Service;


use Drupal\Core\Entity\EntityStorageException;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\soc_content\Service\Manager\ContentManager;

class MenuItemContent extends ContentManager {

  /**
   * @param string $uuid
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  protected function getMenuItemByUuid(string $uuid) {
    return $this->getEntityByUuid('menu_link_content', $uuid);
  }

  /**
   * Create new menu item.
   *
   * @param $data
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\menu_link_content\Entity\MenuLinkContent
   */
  public function createMenuItem($data) {
    // Validate input.
    if (!isset($data['title'])) {
      $this->logger->warning('Trying to create a menu item without title, skipped...');
    }
    elseif (!isset($data['link'])) {
      $this->logger->warning('Trying to create a menu item without link, skipped...');
    }
    elseif (!isset($data['menu_name'])) {
      $this->logger->error('Trying to create a menu item without menu name, skipped...');
    }
    // If input is OK.
    else {
      // Check if menu link already exists.
      $menuLinks = \Drupal::entityQuery('menu_link_content')
        ->condition('title', $data['title'])
        ->condition('link', $data['link'])
        ->condition('menu_name', $data['menu_name'])
        ->execute();

      // If menu item does not exist, create it.
      if (empty($menuLinks)) {
        $newMenuLink = MenuLinkContent::create($data);
        try {
          $newMenuLink->save();
          return $newMenuLink;
        } catch (EntityStorageException $e) {
          $this->logger->error($e->getMessage());
        }
      }
    }
    return FALSE;
  }

}
