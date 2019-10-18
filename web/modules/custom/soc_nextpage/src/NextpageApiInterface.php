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

  public function elementsAndLinks($extIds, $contextId, $langId);

  public function descendantsAndLinks();

  public function elementsByCharTemplate();

  public function characteristicsDictionary();

}
