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
   * @param string $title
   * @param string $menu_name
   * @param array $link
   * @param $data
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\menu_link_content\Entity\MenuLinkContent
   */
  public function createMenuItem(string $title, string $menu_name, array $link, $data = []) {
    // Check if menu link already exists.
    $menuLinksQuery = \Drupal::entityQuery('menu_link_content');
    if (isset($data['uuid'])) {
      $menuLinksQuery->condition('uuid', $data['uuid']);
    }
    else {
      $menuLinksQuery->condition('title', $title);
      $menuLinksQuery->condition('menu_name', $menu_name);
    }
    $menuLinks = $menuLinksQuery->execute();

    // If menu item does not exist, create it.
    if (empty($menuLinks)) {
      $data['title'] = $title;
      $data['menu_name'] = $menu_name;
      $data['link'] = $link;
      $newMenuLink = MenuLinkContent::create($data);
      try {
        $newMenuLink->save();
        return $newMenuLink;
      } catch (EntityStorageException $e) {
        $this->logger->error($e->getMessage());
      }
    }
    return FALSE;
  }

  /**
   * Update existing block content.
   *
   * @param string $uuid
   * @param array $data
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\menu_link_content\Entity\MenuLinkContent
   */
  public function updateMenuItemContent(string $uuid, array $data) {
    // Check if block content already exists.
    /** @var \Drupal\menu_link_content\Entity\MenuLinkContent $menuItemContent */
    if (!$menuItemContent = $this->getMenuItemByUuid($uuid)) {
      $this->logger->warning('Trying to update a menu item who does not exist, skipped...');
    }
    // If input is OK.
    else {
      // Update menu item.
      foreach ($data as $propertyName => $propertyValue) {
        $menuItemContent->set($propertyName, $propertyValue);
      }
      try {
        $menuItemContent->save();
        return $menuItemContent;
      } catch (EntityStorageException $e) {
        $this->logger->error($e->getMessage());
      }
    }
    return FALSE;
  }

}
