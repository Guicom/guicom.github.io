<?php

namespace Drupal\soc_bookmarks\Controller;

use Drupal\soc_content_list\Controller\ContentListController;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\soc_bookmarks\Service\Manager\BookmarkManager;
use Drupal\soc_bookmarks\Service\Manager\BookmarkDownload;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BookmarkController extends ContentListController {

  /** @var \Drupal\soc_bookmarks\Service\Manager\BookmarkManager $bookmarkManager */
  protected $bookmarkManager;

  /** @var \Drupal\soc_bookmarks\Service\Manager\BookmarkDownload $bookmarkDownload */
  protected $bookmarkDownload;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * WishlistController constructor.
   *
   * @param \Drupal\soc_bookmarks\Service\Manager\BookmarkManager $bookmarkManager
   * @param \Drupal\soc_bookmarks\Service\Manager\BookmarkDownload $bookmarkDownload
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(BookmarkManager $bookmarkManager, BookmarkDownload $bookmarkDownload, MessengerInterface $messenger) {
    $this->bookmarkManager = $bookmarkManager;
    $this->bookmarkDownload = $bookmarkDownload;
    $this->messenger = $messenger;
    parent::__construct($bookmarkManager, $messenger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_bookmarks.bookmark_manager'),
      $container->get('soc_bookmarks.bookmark_download'),
      $container->get('messenger')
    );
  }

  /**
   * @param string $item_id
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function addItemAction(string $item_id) {
    if ($this->bookmarkDownload->getFile($item_id)) {
      return parent::addItemAction($item_id);
    }
    return [];
  }

  /**
   * @return \Drupal\soc_bookmarks\Controller\Response
   */
  public function downloadItemsAction() {
    /** @var \Drupal\soc_bookmarks\Service\Manager\BookmarkDownload $download */
    $download = \Drupal::service('soc_bookmarks.bookmark_download');
    return $download->download();
  }
}
