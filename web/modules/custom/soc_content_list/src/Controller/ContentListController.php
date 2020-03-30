<?php

namespace Drupal\soc_content_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\soc_content_list\Service\Manager\ContentListManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Url;
use Drupal\soc_content_list\Service\Helper\ContentListHelper;

class ContentListController extends ControllerBase {

  /** @var \Drupal\soc_content_list\Service\Manager\ContentListManager $contentListManager */
  protected $contentListManager;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * ContentListController constructor.
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
   * @param string $itemId
   *
   * @return array
   */
  public function addItemAction(string $item_id) {
    try {
      $this->contentListManager->loadSavedItems();
    } catch (\Exception $e) {}
    if ($this->contentListManager->add($item_id)) {
      try {
        $this->contentListManager->updateCookie();
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
  public function removeItemAction(string $item_id) {
    try {
      $this->contentListManager->loadSavedItems();
    } catch (\Exception $e) {}
    if ($this->contentListManager->remove($item_id)) {
      try {
        $this->contentListManager->updateCookie();
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
    try {
      if (ContentListHelper::sessionNameExit($this->contentListManager->getlastDeletedSessionName())) {
        $lastDeleted = (isset($_SESSION[$this->contentListManager->getlastDeletedSessionName()])) ?
          $_SESSION[$this->contentListManager->getlastDeletedSessionName()] : '';
      }
    } catch (\Exception $e) {
      $this->messenger->addError($e->getMessage());
    }

    if (!empty($lastDeleted)) {
      try {
        $this->contentListManager->loadSavedItems();
      } catch (\Exception $e) {}
      try {
        foreach ($lastDeleted as $item) {
          $this->contentListManager->add($item);
        }
        $this->contentListManager->updateCookie();
      } catch (\Exception $e) {
        $this->messenger->addError($e->getMessage());
      }
    }

    try {
      if (ContentListHelper::routeExist($this->contentListManager->getItemActionRoute())) {
        $redirect_url = Url::fromRoute($this->contentListManager->getItemActionRoute());
        $response = new RedirectResponse($redirect_url->toString(), 302);
        $response->expire();
        return $response;
      }
    } catch (\Exception $e) {
      $this->messenger->addError($e->getMessage());
    }
    return [];
  }

  /**
   * @param string $type
   *
   * @return \Symfony\Component\HttpFoundation\Response
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
