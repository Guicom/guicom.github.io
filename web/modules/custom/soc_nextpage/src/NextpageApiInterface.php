<?php

namespace Drupal\soc_nextpage;

interface NextpageApiInterface {

  /**
   * Get current API token.
   *
   * @return string
   */
  public function getApiToken(): string;

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
