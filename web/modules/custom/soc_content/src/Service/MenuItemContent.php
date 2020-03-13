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
    $menuLinks = \Drupal::entityQuery('menu_link_content')
      ->condition('title', $title)
      ->condition('menu_name', $menu_name)
      ->execute();

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

}
