<?php
/**
 * @file
 * Contains \Drupal\soc_menu\Routing\RouteSubscriber.
 */

namespace Drupal\soc_menu\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class MenuRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // Replace "some.route.name" below with the actual route you want to override.
    if ($route = $collection->get('we_megamenu.admin.save')) {
      $route->setDefaults(array(
        '_controller' => '\Drupal\soc_menu\Controller\SocMenuSave::saveConfig',
      ));
    }
  }
}
