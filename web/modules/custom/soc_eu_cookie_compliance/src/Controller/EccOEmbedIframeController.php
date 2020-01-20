<?php

/**
 * Alter OEmbedIframeController
 * Alter Render by RGPD access
 */
namespace Drupal\soc_eu_cookie_compliance\Controller;

use Drupal\media\Controller\OEmbedIframeController;
use Drupal\soc_eu_cookie_compliance\Cache\Context\SocomecEccCacheContext;
use Symfony\Component\HttpFoundation\Request;



class EccOEmbedIframeController extends OEmbedIframeController {

  /**
   * Renders an oEmbed resource.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Will be thrown if the 'hash' parameter does not match the expected hash
   *   of the 'url' parameter.
   */
  public function render(Request $request) {
    $soc_ecc_service = \Drupal::service('soc_eu_cookie_compliance.soc_ecc');
    $soc_ecc_service->setCategorie('statistics');

    if (!$soc_ecc_service->hasAccess()) {
      $gpdrMessage = $soc_ecc_service->getMessage();
    }

    if (!empty($gpdrMessage)) {
      // TODO alter output replace response parent by render array
      // Construct cache.
      /*$response = [
        '#markup' => $gpdrMessage,
        '#cache' => [
          'contexts' => [SocomecEccCacheContext::CONTEXT_ID]
          ]
      ];*/
      $response = parent::render($request);
    }
    else {
      $response = parent::render($request);
    }

    return $response;
  }

}
