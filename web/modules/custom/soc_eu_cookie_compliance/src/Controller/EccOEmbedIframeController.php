<?php

use Drupal\media\Controller\OEmbedIframeController;

/**
 * Alter OEmbedIframeController
 * Alter Render by RGPD access
 */
namespace Drupal\soc_eu_cookie_compliance\Controller;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\HtmlResponse;
use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Drupal\media\IFrameMarkup;
use Drupal\media\IFrameUrlHelper;
use Drupal\media\OEmbed\ResourceException;
use Drupal\media\OEmbed\ResourceFetcherInterface;
use Drupal\media\OEmbed\UrlResolverInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


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
      //$response = ['#markup' => $gpdrMessage];
      $response = parent::render($request);
    }
    else {
      $response = parent::render($request);
    }

    return $response;
  }

}
