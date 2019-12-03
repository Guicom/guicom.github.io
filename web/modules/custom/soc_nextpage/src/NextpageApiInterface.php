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
  public function generateApiToken(): string;

  public function elementsAndLinks(array $extIds, $paths, $dcExtIds);

  public function descendantsAndLinks(array $extIds, $onlyOneLevel, $paths, $dcExtIds);

  public function elementsByCharTemplate($charTemplateExtID, $paths);

  public function characteristicsDictionary();

}
