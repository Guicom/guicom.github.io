<?php

namespace Drupal\soc_traceparts\Service\Manager;

use Drupal\soc_traceparts\Service\TracepartsApi;

/**
 * Class TracepartsViewerManager.
 */
class TracepartsViewerManager {

  private $tracepartsApi;

  /**
   * Constructs a new TracepartsViewerManager object.
   */
  public function __construct(TracepartsApi $traceparts_api) {
    $this->tracepartsApi = $traceparts_api;
  }

  /**
   * @param string $part_number
   *
   * @return bool
   */
  public function getViewerAvailability(string $part_number): bool {
    $partData = $this->tracepartsApi->getCadDataAvailability($part_number);
    if (sizeof($partData) && isset($partData['viewerAvailability'])) {
      return (bool) $partData['viewerAvailability'];
    }
    return FALSE;
  }

}
