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
    return $this->returnResults($results);
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
    return $this->returnResults($results);
  }

  /**
   * Get elements by product type.
   *
   * @param $charTemplateExtID
   * @param array $paths
   *
   * @return array|mixed
   */
  public function elementsByCharTemplate($charTemplateExtID, $paths = []) {
    $endpoints = $this->getEndpoints();
    $results = [];
    try {
      $results = $this->call($endpoints['elementsbychartemplate'], [
        'body' => json_encode([
          'CharTemplateExtID' => $charTemplateExtID,
          'Paths' => $paths,
          'ContextID' => $this->getContextId(),
          'LangID' => $this->getLanguageId(),
        ]),
      ]);
    } catch (\Exception $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
    }
    return $this->returnResults($results);
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
    return $this->returnResults($results);
  }

  /**
   * Error management.
   *
   * @param $results
   *
   * @return mixed
   */
  protected function returnResults($results) {
    if (sizeof($results->Results->ResultsExtIDs)) {
      return $results->Results;
    }
    elseif (sizeof($results->Warnings)) {
      foreach ($results->Warnings as $warning) {
        \Drupal::logger('soc_nextpage')->warning($warning->Message);
      }
    }
    elseif (sizeof($results->Errors)) {
      foreach ($results->Errors as $error) {
        \Drupal::logger('soc_nextpage')->error($error->Message);
      }
    }
    return [];
  }

}
