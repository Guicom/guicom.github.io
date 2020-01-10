<?php

namespace Drupal\soc_user\EventSubscriber;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\soc_user\Helper\SocUserHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class SuperAdminAccessPageEventSubscriber.
 */
class SuperAdminAccessPageEventSubscriber implements EventSubscriberInterface {

  /**
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new SuperAdminAccessPageEventSubscriber object.
   */
  public function __construct(RouteMatchInterface $routeMatch) {
    $this->routeMatch = $routeMatch;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['kernel.request'] = ['onKernelRequest'];

    return $events;
  }

  /**
   * This method is called when the kernel.request is dispatched.
   *
   * @param \Symfony\Component\EventDispatcher\Event $event
   *   The dispatched event.
   */
  public function onKernelRequest(Event $event) {
    $routeName = $this->routeMatch->getRouteName();
    switch ($routeName) {
      case 'entity.user.edit_form':
      case 'entity.user.canonical':
        $uid = $this->routeMatch->getRawParameter('user');
        if (SocUserHelper::disableSuperAdminAccount() && $uid == 1) {
          throw new AccessDeniedHttpException();
        }
    }
  }
}
