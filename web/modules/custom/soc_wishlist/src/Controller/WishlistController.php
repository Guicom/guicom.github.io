<?php

namespace Drupal\soc_wishlist\Controller;

use Drupal\soc_content_list\Controller\ContentListController;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\soc_wishlist\Service\Manager\WishlistManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Url;

class WishlistController extends ContentListController {

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
    parent::__construct($wishlistManager, $messenger);

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
   * Export datas
   *
   * @param string $extid
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   */
  public function export(string $type) {
    $response = new Response();

    $types = [
      'csv',
      'xls',
      'xlsx',
      'pdf'
    ];

    if (in_array($type, $types)) {
      /** @var \Drupal\soc_wishlist\Service\Manager\WishlistExport $export */
      $export = \Drupal::service('soc_wishlist.wishlist_export');
      $export->setType($type);
      try {
        return $export->export();
      } catch (\Exception $e) {
        $this->messenger->addError($e->getMessage());
      }
    }
    else {
      $this->messenger->addError(t('Unknown type'));
    }
    return $response;
  }
}
