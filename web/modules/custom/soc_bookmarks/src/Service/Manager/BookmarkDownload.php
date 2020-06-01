<?php

namespace Drupal\soc_bookmarks\Service\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Drupal\Core\Url;

class BookmarkDownload {
  /** session constant. */
  const BOOKMARK_SESSION = 'socomec_bookmark_download';

  /** @var $bookmarkManager */
  protected $bookmarkManager;

  /** @var $settings */
  protected $settings;

  /** @var $folder */
  protected $folder;

  /**
   * BookmarkDownload constructor.
   *
   * @param \Drupal\soc_bookmarks\Service\Manager\BookmarkManager $bookmarkManager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(BookmarkManager $bookmarkManager,
                              ConfigFactoryInterface $configFactory) {
    $this->bookmarkManager = $bookmarkManager;
    $this->settings = $configFactory->getEditable('soc_bookmarks.settings');
  }

  /**
   * Download bookmark.
   */
  public function download() {
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

      $this->setFolder();

      $files = $this->prepareItems($items);

      $output = [];
      if (count($items) === 1) {
        if (!empty($files[0]['filename']) && !empty($files[0]['filepath'])) {
          $output['filename'] = $files[0]['filename'];
          $output['filepath'] = $files[0]['filepath'];
        }
      }
      else {
        if (!empty($files)) {
          $folder = $this->getFolder();
          $fids = array_keys($files);
          sort($fids);
          $fids_hash = md5(implode('.', $fids));
          $zip_filename = 'files_' . substr($fids_hash, 0, 8) . '.zip';
          $zip_filepath = drupal_realpath($folder) .'/'. $zip_filename;
          $zip = new \ZipArchive;
          $opened = $zip->open($zip_filepath, \ZipArchive::CREATE);
          if ($opened) {
            foreach ($files as $file) {
              $zip->addFile($file['filepath'], $file['filename']);
            }
            $zip->close();
            $output['filename'] = $zip_filename;
            $output['filepath'] = $zip_filepath;
          }
        }
      }
    }

    // Prepare response.
    if (!empty($output['filepath'])) {
      $headers['Content-Type'] = \Drupal::service('file.mime_type.guesser')
        ->guess($output['filename']);
      $headers['Content-Disposition'] = 'attachment; filename="' . $output['filename'] . '"';
      $response = new BinaryFileResponse(\Drupal::service('file_system')
        ->realpath($output['filepath']), 200, $headers);
      $response->setContentDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        $output['filename']
      );
      return $response;
    }
    if ($last_route = $this->getLastReferer()) {
      $url = Url::fromRoute($last_route);
      \Drupal::messenger()->addWarning(t('No file available. <a href="@url">Return</a>',['@url' => $url->toString()]), TRUE);
    }
    else {
      \Drupal::messenger()->addWarning(t('No file available.'), TRUE);
    }
    $message = [
      '#theme' => 'status_messages',
      '#message_list' => drupal_get_messages(),
    ];
    return $message;
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

  protected function prepareItems($items) {
    $folder = $this->getFolder();
    $files = [];
    foreach ($items as $item) {
      if ($item['node'] instanceof Node) {
        $file = $this->getFile($item['node']->id());
        $entity = $file->entity;
        $filename = $entity->getFileName();
        $filepath = drupal_realpath($entity->getFileUri());
        if ($filepath) {
          $image_uri = file_unmanaged_save_data($filepath, $folder . $filename, FILE_EXISTS_RENAME);
        }
        else {
          $external_image = file_get_contents($entity->getFileUri());
          $image_uri = file_unmanaged_save_data($external_image, $folder . $filename, FILE_EXISTS_RENAME);
        }
        $filepath = drupal_realpath($image_uri);
        if (!empty($filepath)) {
          $pathinfo = pathinfo($filepath);
          $files[] = [
            'entity' => $entity,
            'filename' => $pathinfo['basename'],
            'filepath' => $filepath
          ];
        }
      }
    }
    return $files;
  }

  protected function setFolder() {
    $uuid_service = \Drupal::service('uuid');
    $uuid = $uuid_service->generate();
    $this->folder = 'temporary://webfactory/bookmarks/'.$uuid.'/';
    file_prepare_directory($this->folder, FILE_CREATE_DIRECTORY);
  }

  protected function getFolder() {
    return $this->folder;
  }

  protected function getLastReferer() {
    $request = \Drupal::request();
    $referer = $request->headers->get('referer');
    if ($referer
      && ($base_url = $request::createFromGlobals()->getSchemeAndHttpHost())
      && ($alias = substr($referer, strlen($base_url)))
      && ($url_object = \Drupal::service('path.validator')->getUrlIfValid($alias))) {
      return $url_object->getRouteName();
    }
    return FALSE;
  }
}
