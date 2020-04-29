<?php

namespace Drupal\soc_traceparts\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\soc_traceparts\Service\Manager\TracepartsDownloadsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DownloadController extends ControllerBase {

  /**
   * Tempstore service.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /** @var TracepartsDownloadsManager $downloadManager */
  protected $downloadManager;

  /**
   * DownloadController constructor.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempStoreFactory
   * @param \Drupal\soc_traceparts\Service\Manager\TracepartsDownloadsManager $downloadsManager
   */
  public function __construct(PrivateTempStoreFactory $tempStoreFactory,
                              TracepartsDownloadsManager $downloadsManager) {
    $this->tempStoreFactory = $tempStoreFactory;
    $this->downloadManager = $downloadsManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('soc_traceparts.traceparts_downloads_manager')
    );
  }

  public function downloadPage() {
    $tempStore = $this->tempStoreFactory->get('soc_traceparts_user_data');
    $part_number = $tempStore->get('part_number');
    $format_id = $tempStore->get('format_id');
    $user_email = $tempStore->get('email');
    $downloadLink = $this->downloadManager->getDownloadLink($part_number, $format_id, $user_email);
    return [
      '#markup' => $downloadLink,
    ];
  }

}
