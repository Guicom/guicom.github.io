<?php

namespace Drupal\soc_nextpage\Service;

use Drupal\soc_nextpage\NextpageApiInterface;

class NextpageApi extends NextpageBaseApi implements NextpageApiInterface {

  /**
   * Get an element and its characteristics.
   *
   * @param $extIds
   * @param array $paths
   * @param array $dcExtIds
   *
   * @return array|mixed
   */
  public function elementsAndLinks($extIds, $paths = [], $dcExtIds = []) {
    $endpoints = $this->getEndpoints();
    $results = [];
    try {
      $results = $this->call($endpoints['elementsandlinks'], [
        'body' => json_encode([
          'ElementsExtIDs' => $extIds,
          'Paths' => $paths,
          'ContextID' => $this->getContextId(),
          'LangID' => $this->getLanguageId(),
          'DCExtIDs' => $dcExtIds,
        ]),
      ]);
    } catch (\Exception $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
    }
    return $results;
  }

  /**
   * Get an hierarchy.
   *
   * @param $extIds
   * @param bool $onlyOneLevel
   * @param array $paths
   * @param array $dcExtIds
   *
   * @return array|mixed
   */
  public function descendantsAndLinks($extIds, $onlyOneLevel = TRUE, $paths = [], $dcExtIds = []) {
    $endpoints = $this->getEndpoints();
    $results = [];
    try {
      $results = $this->call($endpoints['descendantsandlinks'], [
        'body' => json_encode([
          'ElementsExtIDs' => $extIds,
          'Paths' => $paths,
          'ContextID' => $this->getContextId(),
          'LangID' => $this->getLanguageId(),
          'DCExtIDs' => $dcExtIds,
          'OnlyOneLevel' => $onlyOneLevel,
        ]),
      ]);
    } catch (\Exception $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
    }
    return $results;
  }

  /**
   * Get elements by product type.
   *
   */
  public function elementsByCharTemplate() {
    // TODO: Implement ElementsByCharTemplate() method.
  }

  /**
   * Get the characteristics dictionary.
   *
   * @return array|mixed
   */
  public function characteristicsDictionary() {
    $endpoints = $this->getEndpoints();
    $results = [];
    try {
      $results = $this->call($endpoints['dicocarac'] . '/' . $this->getLanguageId(),
        NULL, 'GET');
    } catch (\Exception $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
    }
    return $results;
  }

}
