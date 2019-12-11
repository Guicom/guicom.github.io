<?php

namespace Drupal\soc_wishlist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\soc_wishlist\Service\Manager\WishlistManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WishlistController extends ControllerBase {

  /** @var \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager */
  private $wishlistManager;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * WishlistController constructor.
   *
   * @param \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(WishlistManager $wishlistManager, MessengerInterface $messenger) {
    $this->wishlistManager = $wishlistManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_wishlist.wishlist_manager'),
      $container->get('messenger')
    );
  }

  /**
   * @param string $extid
   *
   * @return array
   */
  public function addItemAction(string $extid) {
    $this->wishlistManager->loadSavedItems();
    if ($this->wishlistManager->add($extid)) {
      try {
        $this->wishlistManager->updateCookie();
      } catch (\Exception $e) {
        $this->messenger->addError($e->getMessage());
      }
    }
    return [];
  }

  /**
   * @param string $extid
   *
   * @return array
   */
  public function removeItemAction(string $extid) {
    $this->wishlistManager->loadSavedItems();
    if ($this->wishlistManager->remove($extid)) {
      try {
        $this->wishlistManager->updateCookie();
      } catch (\Exception $e) {
        $this->messenger->addError($e->getMessage());
      }
    }
    return [];
  }

}
