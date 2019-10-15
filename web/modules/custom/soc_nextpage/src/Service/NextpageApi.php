<?php

namespace Drupal\soc_nextpage\Service;

use Drupal\soc_nextpage\NextpageApiInterface;

class NextpageApi extends NextpageBaseApi implements NextpageApiInterface {

  public function elementsAndLinks($extIds, $contextId = 1, $langId = 1) {
    $results = [];
    try {
      $results = $this->call('/api/sdk-ext/element/ElementsAndLinks', [
        'body' => json_encode([
          'ElementsExtIDs' => $extIds,
          'Paths' => [],
          'ContextID' => $contextId,
          'LangID' => $langId,
          'DCExtIDs' => [],
        ]),
      ]);
    } catch (\Exception $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
    }
    return $results;
  }

  public function descendantsAndLinks() {
    // TODO: Implement DescendantsAndLinks() method.
  }

  public function elementsByCharTemplate() {
    // TODO: Implement ElementsByCharTemplate() method.
  }

  /**
   * @param int $langId
   *
   * @return array|mixed
   */
  public function characteristicsDictionary($langId = 1) {
    $results = [];
    try {
      $results = $this->call('/api/sdk-ext/dicocarac/GetAll/' . $langId, NULL, 'GET');
    } catch (\Exception $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
    }
    return $results;
  }

}
