<?php

namespace Drupal\soc_bookmarks\Service\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_bookmarks\Service\Manager\BookmarkManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Drupal\Core\File\FileSystemInterface;

class BookmarkDownload {
  /** session constant. */
  const BOOKMARK_SESSION = 'socomec_bookmark_download';

  /** @var $bookmarkManager */
  protected $bookmarkManager;

  /** @var $settings */
  protected $settings;

  /**
   * BookmarkDownload constructor.
   *
   * @param \Drupal\soc_bookmarks\Service\Manager\BookmarkManager $bookmarkManager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(BookmarkManager $bookmarkManager,
                              ConfigFactoryInterface $configFactory) {
    $this->bookmarkManager = $bookmarkManager;
    $this->settings = $configFactory->getEditable('soc_wishlist.settings');
  }

  /**
   * WishlistExport manage export
   */
  public function download() {
    // @todo SOCSOB-411
    return [];
    /*
    $tmpItems = $this->bookmarkManager->loadSavedItems();
    $downloadList = ($_SESSION[self::BOOKMARK_SESSION]) ?? NULL;
    $items = [];
    if (!empty($downloadList)) {
      foreach ($downloadList as $selectedItem) {
        if (isset($tmpItems[$selectedItem])) {
          $items[$selectedItem] = $tmpItems[$selectedItem];
        }
      }
    }

    if (!empty($items) && is_array($items)) {
      if (count($items) === 1) {
        foreach ($items as $item) {
          if ($item['node'] instanceof Node) {
            $file = $this->getFile($item['node']->id());
            $entity = $file->entity;
            $filename = $entity->getFileName();
            // Set archive name/path.
            $folder = 'temporary://bookmark/';
            $filepath = $folder . $filename;
            file_prepare_directory($folder, FILE_CREATE_DIRECTORY);
            $filepath = drupal_realpath($entity->getFileUri());
            if (!$filepath) {
              $filepath = \Drupal::service('file_system')->copy($entity->getFileUri(), $filepath, FileSystemInterface::EXISTS_REPLACE);
            }
            // Prepare response.
            $headers['Content-Type'] = \Drupal::service('file.mime_type.guesser')
              ->guess($filename);
            $headers['Content-Disposition'] = 'attachment; filename="' . $filename . '"';
            $response = new BinaryFileResponse(\Drupal::service('file_system')
              ->realpath($filepath), 200, $headers);
            $response->setContentDisposition(
              ResponseHeaderBag::DISPOSITION_ATTACHMENT,
              $filename
            );
          }
        }
      }
      else {

      }
    }

    return $response;
    */
  }

  /**
   * @param $item_id
   *
   * @return bool
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getFile($item_id) {
    $node = \Drupal::entityManager()->getStorage('node')->load($item_id);
    if ($node) {
      $downloadable = $node->get('field_res_downloadable')->getString();
      if (!empty($downloadable) && $downloadable == 1) {
        $file = $node->get('field_res_remote_file_url');
        if (!empty($file) && !empty($file->target_id)) {
          return $file;
        }
      }
    }
    return FALSE;
  }
}



