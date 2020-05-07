<?php


namespace Drupal\soc_traceparts\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\soc_traceparts\Service\Manager\TracepartsDownloadsManager;
use Drupal\soc_traceparts\Service\Manager\TracepartsUserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class TracepartsForm extends FormBase {

  /**
   * Tempstore service.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /** @var TracepartsUserManager $tracepartsUser */
  protected $tracepartsUser;

  /** @var TracepartsDownloadsManager $downloadManager */
  protected $downloadManager;

  /**
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempStoreFactory
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\soc_traceparts\Service\Manager\TracepartsUserManager $traceparts_user
   * @param \Drupal\soc_traceparts\Service\Manager\TracepartsDownloadsManager $downloadsManager
   */
  public function __construct(PrivateTempStoreFactory $tempStoreFactory,
                              AccountInterface $current_user,
                              TracepartsUserManager $traceparts_user,
                              TracepartsDownloadsManager $downloadsManager) {
    $this->tempStoreFactory = $tempStoreFactory;
    $this->currentUser = $current_user;
    $this->tracepartsUser = $traceparts_user;
    $this->downloadManager = $downloadsManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('current_user'),
      $container->get('soc_traceparts.traceparts_user_manager'),
      $container->get('soc_traceparts.traceparts_downloads_manager')
    );
  }

  /**
   * @param $values
   *
   * @return bool|string|null
   */
  public function getDownloadLink($values) {
    $tempStore = $this->tempStoreFactory->get('soc_traceparts_user_data');
    try {
      $tempStore->set('email', $values['email']);
      $tempStore->set('part_number', $values['part_number']);
      $tempStore->set('format_id', $values['format_id']);
      $partNumber = $tempStore->get('part_number');
      $formatId = $tempStore->get('format_id');
      $userEmail = $tempStore->get('email');
      $formats = $this->downloadManager->getDownloadableFormats($partNumber);
      $formatName = $formats[$formatId];
      $buttonLabel = $this->t('Download as @format_name', [
        '@format_name' => $formatName,
      ]);
      $downloadUrl = $this->downloadManager->getDownloadLink($partNumber, $formatId, $userEmail);
      return [
        '#theme' => 'soc_traceparts_download_link',
        '#button_label' => $buttonLabel,
        '#download_url' => $downloadUrl,
      ];
    }
    catch (\Exception $e) {
      $this->logger('soc_traceparts')->alert($e->getMessage());
    }
    return FALSE;
  }

}
