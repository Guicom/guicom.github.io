<?php

namespace Drupal\soc_nextpage;

interface NextpageApiInterface {

  const AUTH_URI = '/api/auth';

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

  public function elementsAndLinks();

  public function descendantsAndLinks();

  public function elementsByCharTemplate();

  public function characteristicsDictionary();

}
