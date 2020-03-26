<?php

namespace Drupal\soc_nextpage\Controller;


use Drupal;
use Drupal\soc_nextpage\Exception\InvalidTokenException;

class Test {

  use Drupal\Core\StringTranslation\StringTranslationTrait;

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
  public function characteristicsDictionary($langId = 2) {
    $characteristics = $this->nextPageApi->synchroniseCharacteristicsDictionary();
    return [
      "#markup" => $this->t('Synchronisation is done'),
    ];
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

  public function elementsByCharTemplate() {
    $descendants = $this->nextPageApi->elementsByCharTemplate(['FNiveau2_CODE_FAMILLE_3_066']);
    kint($descendants);
    return [];
  }

}
