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

  /**
   * @return array
   */
  public function downloadPage() {
    $tempStore = $this->tempStoreFactory->get('soc_traceparts_user_data');
    $partNumber = $tempStore->get('part_number');
    $formatId = $tempStore->get('format_id');
    $userEmail = $tempStore->get('email');
    $formats = $this->downloadManager->getDownloadableFormats($partNumber);
    $formatName = $formats[$formatId];
    $buttonLabel = $this->t('Download as @format_name', [
      '@format_name' => $formatName,
    ]);
    $downloadUrl = $this->downloadManager->getDownloadLink($partNumber, $formatId, $userEmail);
    $title = $this->t('Thank you!');
    return [
      '#theme' => 'soc_traceparts_download_page',
      '#title' => $title,
      '#button_label' => $buttonLabel,
      '#download_url' => $downloadUrl,
    ];
  }

  public function downloadPageTitle() {
    $tempStore = $this->tempStoreFactory->get('soc_traceparts_user_data');
    $partNumber = $tempStore->get('part_number');
    $formatId = $tempStore->get('format_id');
    $formats = $this->downloadManager->getDownloadableFormats($partNumber);
    $formatName = $formats[$formatId];
    return $this->t('Download @part_number @format_name 3D model', [
      '@part_number' => $partNumber,
      '@format_name' => $formatName,
    ]);
  }

}
