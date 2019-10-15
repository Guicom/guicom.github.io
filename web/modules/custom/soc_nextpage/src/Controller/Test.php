<?php

namespace Drupal\soc_nextpage\Controller;


use Drupal\soc_nextpage\Exception\InvalidTokenException;

class Test {

  /** @var \Drupal\soc_nextpage\Service\NextpageApi $ws */
  public $nextPageApi;

  public function __construct() {
    $this->nextPageApi = \Drupal::service('soc_nextpage.nextpage_api');
  }

  public function getToken() {
    try {
      $token = $this->nextPageApi->generateApiToken();
    } catch (InvalidTokenException $e) {
    }
    return [
      '#markup' => $token,
    ];
  }

}
