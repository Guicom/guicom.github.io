<?php

namespace Drupal\soc_nextpage;

interface NextpageApiInterface {

  /**
   * Get current API token.
   *
   * @return string|null
   */
  public function getApiToken();

  /**
   * Check an API token validity.
   *
   * @return string
   */
  public function generateApiToken(): object ;

  public function elementsAndLinks(array $extIds, $paths, $dcExtIds);

  public function descendantsAndLinks($onlyOneLevel, $paths, $dcExtIds);

  public function elementsByCharTemplate($charTemplateExtID, $paths);

  /**
   * @param string $languageId
   *
   * @param bool $ws
   *
   * @return mixed
   */
  public function characteristicsDictionary(string $languageId, bool $ws);

}
