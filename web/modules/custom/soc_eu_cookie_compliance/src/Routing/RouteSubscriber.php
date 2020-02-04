<?php
/**
 * @file
 * Contains \Drupal\soc_eu_cookie_compliance\Routing\RouteSubscriber.
 */

namespace Drupal\soc_eu_cookie_compliance\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change default route
    if ($route = $collection->get('media.oembed_iframe')) {
      $routeDefaults = $route->getDefaults();
      $routeDefaults['_controller'] = '\Drupal\soc_eu_cookie_compliance\Controller\EccOEmbedIframeController::render';
      $route->setDefaults($routeDefaults);
    }
  }

}