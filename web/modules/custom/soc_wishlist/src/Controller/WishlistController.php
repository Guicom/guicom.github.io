<?php

namespace Drupal\soc_wishlist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\soc_wishlist\Service\Manager\WishlistManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WishlistController extends ControllerBase {

  /** @var \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager */
  private $wishlistManager;

  /**
   * WishlistController constructor.
   *
   * @param \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager
   */
  public function __construct(WishlistManager $wishlistManager) {
    $this->wishlistManager = $wishlistManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_wishlist.wishlist_manager')
    );
  }

  /**
   * @param $extid
   */
  public function addItemAction($extid) {}

  /**
   * @param $extid
   */
  public function removeItemAction($extid) {}

}
