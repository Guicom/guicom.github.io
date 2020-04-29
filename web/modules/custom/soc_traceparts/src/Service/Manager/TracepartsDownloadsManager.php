<?php

namespace Drupal\soc_traceparts\Service\Manager;

use Drupal\soc_traceparts\Service\TracepartsApi;

/**
 * Class TracepartsDownloadsManager.
 */
class TracepartsDownloadsManager {

  private $tracepartsApi;

  /**
   * Constructs a new TracepartsDownloadsManager object.
   *
   * @param \Drupal\soc_traceparts\Service\TracepartsApi $traceparts_api
   */
  public function __construct(TracepartsApi $traceparts_api) {
    $this->tracepartsApi = $traceparts_api;
  }

  /**
   * @param string $part_number
   *
   * @return array
   */
  public function getDownloadableFormats(string $part_number): array {
    $partData = $this->tracepartsApi->getCadDataAvailability($part_number);
    if (sizeof($partData) && isset($partData['cadFormatList'])) {
      $downloadableFormats = [];
      $cadFormatList = $partData['cadFormatList'];
      foreach ($cadFormatList as $cadFormat) {
        $downloadableFormats[$cadFormat->cadFormatId] = $cadFormat->cadFormatName;
      }
      return $downloadableFormats;
    }
    return [];
  }

}
