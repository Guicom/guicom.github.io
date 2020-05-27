<?php

namespace Drupal\soc_traceparts\Service\Manager;

use Drupal\soc_traceparts\Service\TracepartsApi;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TracepartsDownloadsManager.
 */
class TracepartsDownloadsManager {

  /**
   * @var \Drupal\soc_traceparts\Service\TracepartsApi
   */
  protected $tracepartsApi;

  /**
   * Constructs a new TracepartsDownloadsManager object.
   *
   * @param \Drupal\soc_traceparts\Service\TracepartsApi $traceparts_api
   */
  public function __construct(TracepartsApi $traceparts_api) {
    $this->tracepartsApi = $traceparts_api;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_traceparts.traceparts_api')
    );
  }

  /**
   * @param string $part_number
   *
   * @return array
   */
  public function getDownloadableFormats(string $part_number): array {
    $cid = 'soc_traceparts:downloadable_formats:' . $part_number;
    $data = [];
    if ($cache = \Drupal::cache()->get($cid)) {
      $data = $cache->data;
    }
    else {
      $partData = $this->tracepartsApi->getCadDataAvailability($part_number);
      if (sizeof($partData) && isset($partData['cadFormatList'])) {
        $downloadableFormats = [];
        $cadFormatList = $partData['cadFormatList'];
        foreach ($cadFormatList as $cadFormat) {
          $downloadableFormats[$cadFormat->cadFormatId] = $cadFormat->cadFormatName;
        }
        $data = $downloadableFormats;
        \Drupal::cache()->set($cid, $data, strtotime('+1 day'));
      }
    }
    return $data;
  }

  /**
   * @param string $part_number
   * @param int $format_id
   * @param string $user_email
   *
   * @return string
   */
  public function getDownloadLink(string $part_number, int $format_id, string $user_email): ?string {
    $data = [
      'part_number' => $part_number,
      'format_id' => $format_id,
      'email' => $user_email,
    ];
    $downloadData = $this->tracepartsApi->downloadCadPath($data);
    if (sizeof($downloadData)) {
      return $downloadData['filesPath'][0]->path;
    }
    return NULL;
  }

}
