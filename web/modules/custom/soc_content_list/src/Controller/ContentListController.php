<?php

namespace Drupal\soc_content_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\redirect\Entity\Redirect;
use Drupal\soc_content_list\Service\Manager\ContentListManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;


class ContentListController extends ControllerBase {

  /** @var \Drupal\soc_content_list\Service\Manager\ContentListManager $wishlistManager */
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
   * @param \Drupal\soc_content_list\Service\Manager\ContentListManager $contentListManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(ContentListManager $contentListManager, MessengerInterface $messenger) {
    $this->contentListManager = $contentListManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_content_list.wishlist_manager'),
      $container->get('messenger')
    );
  }

  /**
   * @param string $extid
   *
   * @return array
   */
  public function addItemAction(string $extid) {
    try {
      $this->wishlistManager->loadSavedItems();
    } catch (\Exception $e) {}
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
    try {
      $this->wishlistManager->loadSavedItems();
    } catch (\Exception $e) {}
    if ($this->wishlistManager->remove($extid)) {
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
   * @return bool|\Symfony\Component\HttpFoundation\Response
   */
  public function undoRemoveItemAction() {
    $lastDeleted = (isset($_SESSION['socomec_wishlist_last_deleted'])) ? $_SESSION['socomec_wishlist_last_deleted'] : '';
    if (!empty($lastDeleted)) {
      try {
        $this->wishlistManager->loadSavedItems();
      } catch (\Exception $e) {}
      try {
        foreach ($lastDeleted as $item) {
          $this->wishlistManager->add($item);
        }
        $this->wishlistManager->updateCookie();
      } catch (\Exception $e) {
        $this->messenger->addError($e->getMessage());
      }
    }
    $redirect_url = Url::fromRoute('soc_content_list.edit_wishlist');
    $response = new RedirectResponse($redirect_url->toString(), 302);
    $response->expire();
    return $response;
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
      /** @var \Drupal\soc_content_list\Service\Manager\WishlistExport $export */
      $export = \Drupal::service('soc_content_list.wishlist_export');
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
