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
      $this->logger->error($e->getMessage());
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
  public function descendantsAndLinks($onlyOneLevel = FALSE, $paths = [], $dcExtIds = [], $extId = '') {
    $extId = ($extId == '' ? $this->extIds : $extId);
    $endpoints = $this->getEndpoints();
    $results = [];
    $auth = $this->getAuthStatus() === 0 ? FALSE : TRUE;
    try {
      $results = $this->call($endpoints['descendantsandlinks'], [
        'body' => json_encode([
          'ElementsExtIDs' => [$extId],
          'Paths' => $paths,
          'ContextID' => $this->getContextId(),
          'LangID' => 2,
          'DCExtIDs' => $dcExtIds,
          'OnlyOneLevel' => $onlyOneLevel,
        ]),
      ], 'POST', 'json', $auth, 5);
    } catch (\Exception $e) {
      $this->logger->error($e->getMessage());
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
      $this->logger->error($e->getMessage());
    }
    return $this->returnResults($results);
  }

  /**
   * Get the characteristics dictionary.
   *
   * @param $languageId
   *
   * @return array|mixed
   */
  public function characteristicsDictionary($languageId) {
    $endpoints = $this->getEndpoints();
    $results = [];
    $dictionary = [];
    try {
      $results = $this->call($endpoints['dicocarac'],
        NULL, 'GET', 'json', FALSE);
      // Build dictionary using extid for easer search.
      foreach ($results->Results->Caracs as $carac) {
        $dictionary[$carac->ExtID] = $carac;
      }
    } catch (\Exception $e) {
      $this->logger->error($e->getMessage());
    }
    return $dictionary;
  }

  /**
   * Error management.
   *
   * @param $results
   *
   * @return mixed
   */
  protected function returnResults($results) {
    if (isset($results->Results) && sizeof($results->Results->ResultsExtIDs) || sizeof($results->Results->Caracs)) {
      return $results->Results;
    }
    elseif (sizeof($results->Warnings)) {
      foreach ($results->Warnings as $warning) {
        $this->logger->warning($warning->Message);
      }
    }
    elseif (sizeof($results->Errors)) {
      foreach ($results->Errors as $error) {
        $this->logger->error($error->Message);
      }
    }
    return [];
  }

  /**
   * Synchronises Dictionary and save the json file inside the data folder.
   *
   */
  public function synchroniseCharacteristicsDictionary($langId = 2){
    $characteristics = $this->characteristicsDictionary($langId);
    $filename = 'characteristics_dictionary.json';
    $app_root = \Drupal::root();

    $fh = fopen($app_root . '/../data/' . $filename, 'w') or die('Error opening output file');
    fwrite($fh, json_encode($characteristics));
    fclose($fh);

    \Drupal::logger('soc_nextpage')
      ->info($this->t('The file has been saved to @file', ['@file' => $filename]));
    return TRUE;
  }

}
