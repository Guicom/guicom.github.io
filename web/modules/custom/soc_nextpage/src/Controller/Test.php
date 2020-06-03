<?php

namespace Drupal\soc_nextpage\Controller;


use Drupal;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\soc_nextpage\Exception\InvalidTokenException;
use Symfony\Component\HttpFoundation\JsonResponse;

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
      throw new InvalidTokenException($e->getMessage(), 1);
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
    $elements = $this->nextPageApi->elementsAndLinks('', ['##LinkNodeFPR'], []);
    return new JsonResponse($elements);
  }

  public function descendantsAndLinks() {
    $descendants = $this->nextPageApi->descendantsAndLinks(FALSE, ['##LinkNodeFPR'], [], '');
    return new JsonResponse($descendants);
  }

  public function elementsByCharTemplate() {
    $descendants = $this->nextPageApi->elementsByCharTemplate(['FNiveau2_CODE_FAMILLE_3_066']);
    kint($descendants);
    return [];
  }

}
