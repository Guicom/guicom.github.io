<?php

namespace Drupal\soc_nextpage\Service\Breadcrumb;

use Drupal\soc_nextpage\Service\ProductReference;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;

class SocBreadcrumbProductReference implements BreadcrumbBuilderInterface {


  /**
   * @var \Drupal\soc_nextpage\Service\ProductReference
   */
  protected $productReference;


  /**
   * SocBreadcrumbProductReference constructor.
   *
   * @param \Drupal\soc_nextpage\Service\ProductReference $productReference
   */
  public function __construct(ProductReference $productReference) {
    $this->productReference = $productReference;
  }

   /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    if ($node = $route_match->getParameter('node')) {
      if ($node->bundle() === 'product_reference') {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addLink(Link::createFromRoute(t('Home'), '<front>'));
    $breadcrumb->addLink(Link::createFromRoute(t('Products'), '<none>'));

    // Get the node for the current page
    $node = $route_match->getParameter('node');
    if ($families = $this->productReference->getFamiliesLinkByProductReference($node)) {
      foreach ($families as $family) {
        $breadcrumb->addLink($family);
      }
      
      if ($nodeParent = $this->productReference->getParentProductByProductReference($node)) {
        if (!empty($nodeParent->getTitle()) && !empty($nodeParent->id())) {
          $breadcrumb->addLink(Link::createFromRoute($nodeParent->getTitle(),
            'entity.node.canonical',
            ['node' => $nodeParent->id()])
          );
        }
      }
    }

    // This breadcrumb builder is based on a route parameter, and hence it
    // depends on the 'route' cache context.
    $breadcrumb->addCacheContexts(['route']);
    return $breadcrumb;
  }
}
