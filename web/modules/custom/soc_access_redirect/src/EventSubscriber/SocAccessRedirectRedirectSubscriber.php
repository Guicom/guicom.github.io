<?php

/**
 * @file
 * Contains \Drupal\my_module\EventSubscriber\MyModuleRedirectSubscriber
 */

namespace Drupal\soc_access_redirect\EventSubscriber;

use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Cookie;


class SocAccessRedirectRedirectSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // This announces which events you want to subscribe to.
    // We only need the request event for this example.  Pass
    // this an array of method names
    return([
      KernelEvents::REQUEST => [
        ['redirectSocContent', 1],
      ],
      KernelEvents::RESPONSE => [
        ['setCookie', 2],
      ],
    ]);
  }

  /**
   * Redirect requests for my_content_type node detail pages to node/123.
   *
   * @param GetResponseEvent $event
   * @return void
   * @throws \Exception
   */
  public function redirectSocContent(GetResponseEvent $event) {
    $request = $event->getRequest();

    // This is necessary because this also gets called on
    // node sub-tabs such as "edit", "revisions", etc.  This
    // prevents those pages from redirected.
    if ($request->attributes->get('_route') !== 'entity.node.canonical') {
      return;
    }

    // get current node
    /** @var Node $node */
    $node = $request->attributes->get('node');

    // check if we are in node
    // Check if we are in content type thank_you_page
    if(!is_null($node) && strcmp($node->getType(),'thank_you_page') == 0) {
      // check if node have landing page
      if(!empty($field_landing_page_id = $node->get('field_landing_page')->target_id)) {
         // Check if we are cookies 'soc_landing_page_visited'
        /** @var Cookie $cookies */
        $cookies = $request->cookies->get('soc_landing_page_visited');
        if(!empty($cookies)) {
          // Get values of cookie
          $cookies = unserialize($cookies);
          if(in_array($field_landing_page_id, $cookies)) {
            return;
          }
        }
        $redirect_url = Url::fromRoute('entity.node.canonical', ['node' => $field_landing_page_id]);
        // This is where you set the destination.
        $response = new RedirectResponse($redirect_url->toString(), 301);
        $response->expire();
        $event->setResponse($response);
      }
      else{
        $redirect_url = Url::fromRoute('<front>');
        $response = new RedirectResponse($redirect_url->toString(), 301);
        $response->expire();
        $event->setResponse($response);
      }
    }
  }


  /**
   * Redirect requests for my_content_type node detail pages to node/123.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *
   * @return void
   */
  public function setCookie(FilterResponseEvent $event) {
    $request = $event->getRequest();

    // This is necessary because this also gets called on
    // node sub-tabs such as "edit", "revisions", etc.  This
    // prevents those pages from redirected.
    if ($request->attributes->get('_route') !== 'entity.node.canonical') {
      return;
    }

    // if content type === landing_page set up cookie
    if ($request->attributes->get('node')->getType() === 'landing_page') {
      $response = $event->getResponse();
      if ($response) {
        $cookies = $request->cookies->get('soc_landing_page_visited');
        if(empty($cookies)){
          $item = [$request->attributes->get('node')->id()];
          $cookie = new Cookie('soc_landing_page_visited', serialize($item));
        }
        else{
          $items = unserialize($cookies);
          if (!in_array($request->attributes->get('node')->id(), $items)) {
            $items[] = $request->attributes->get('node')->id();
          }
          $cookie = new Cookie('soc_landing_page_visited', serialize($items));
        }
        $response->headers->setCookie($cookie);
        $event->setResponse($response);
      }
    }

    // If content !== thank_you_page exit.
    if ($request->attributes->get('node')->getType() !== 'thank_you_page') {
      return;
    }

    // get current node
    $node = $request->attributes->get('node');
    if(!empty($node->get('field_landing_page')->getValue()[0]['target_id'])){
      // get landing_page id
      $field_landing_page_id = $node->get('field_landing_page')->getValue()[0]['target_id'];
      // get landing_page_visited cookie and compare with landing page id
      $cookies = $request->cookies->get('soc_landing_page_visited');
      if(!empty($cookies)){
        $cookies = unserialize($cookies);
        if (in_array($field_landing_page_id, $cookies)) {
          if(!empty($node->get('field_file')->getValue()[0]['target_id'])){
            $field_file_id = $node->get('field_file')->getValue()[0]['target_id'];
          }
          if(!empty($field_file_id)){
            $response = $event->getResponse();
            if ($response) {
              $cookies = $request->cookies->get('soc_private_media_access');
              if(empty($cookies)){
                $item = [$field_file_id];
                $cookie = new Cookie('soc_private_media_access', serialize($item));
              }
              else{
                $items = unserialize($cookies);
                if (!in_array($field_file_id, $items)) {
                  $items[] = $field_file_id;
                }
                $cookie = new Cookie('soc_private_media_access', serialize($items));
              }
              $response->headers->setCookie($cookie);
              $event->setResponse($response);
            }
          }
        }
      }
    }
  }
}
