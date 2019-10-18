<?php

namespace Drupal\soc_nextpage\Controller;


use Drupal\soc_nextpage\Exception\InvalidTokenException;

class Test {

  /** @var \Drupal\soc_nextpage\Service\NextpageApi $ws */
  public $nextPageApi;

  /**
   * Test constructor.
   */
  public function __construct() {
    $this->nextPageApi = \Drupal::service('soc_nextpage.nextpage_api');
  }

  /**
   * @return array
   */
  public function getToken() {
    try {
      $token = $this->nextPageApi->generateApiToken();
    } catch (InvalidTokenException $e) {
    }
    return [
      '#markup' => $token,
    ];
  }

  /**
   * @param int $langId
   *
   * @return array
   */
  public function characteristicsDictionary($langId = 1) {
    $characteristics = $this->nextPageApi->characteristicsDictionary();
    kint($characteristics);
    return [];
  }

  public function elementsAndLinks() {
    $elements = $this->nextPageApi->elementsAndLinks(['P_000517']);
    kint($elements);
    return [];
  }

  public function descendantsAndLinks() {
    $descendants = $this->nextPageApi->descendantsAndLinks(['FNiveau2_CODE_FAMILLE_3_066']);
    kint($descendants);
    return [];
  }

}
