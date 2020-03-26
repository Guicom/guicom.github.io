<?php

namespace Drupal\soc_content_list\Service\Helper;

class ContentListHelper {

  /**
   * @param $name
   *
   * @return bool
   * @throws \Exception
   */
  public static function sessionNameExit($name) {
    if (!empty($name)) {
      return TRUE;
    }
    throw new \Exception(t('Session : @session_name not exist.', ['@session_name' => $name]));
  }

  /**
   * @param $route_name
   *
   * @return bool
   * @throws \Exception
   */
  public static function routeExist($route_name) {
    if (!empty($route_name)) {
      /* @var \Drupal\Core\Routing\RouteProviderInterface $route_provider */
      $route_provider = \Drupal::service('router.route_provider');
      if (count($route_provider->getRoutesByNames([$route_name])) === 1) {
        return TRUE;
      }
    }
    throw new \Exception(t('Route : @route_name not exist.', ['@route_name' => $route_name]));
  }
}
