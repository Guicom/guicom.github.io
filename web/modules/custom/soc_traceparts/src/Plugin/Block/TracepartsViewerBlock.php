<?php

namespace Drupal\soc_traceparts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\soc_traceparts\Service\Manager\TracepartsViewerManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Node cached block that display node's ID.
 *
 * @Block(
 *   id = "traceparts_viewer_block",
 *   admin_label = @Translation("Traceparts viewer block")
 * )
 */
class TracepartsViewerBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /** @var TracepartsViewerManager $viewerManager */
  protected $viewerManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition,
      TracepartsViewerManager $viewer_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->viewerManager = $viewer_manager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('soc_traceparts.traceparts_viewer_manager')
    );
  }

  public function build() {
    $build = [];
    //if node is found from routeMatch create a markup with node ID's.
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      $build['node_id'] = array(
        '#markup' => '<p>' . $node->id() . '<p>',
      );
    }
    return $build;
  }

  public function getCacheTags() {
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      return Cache::mergeTags(parent::getCacheTags(), array('node:' . $node->id()));
    } else {
      return parent::getCacheTags();
    }
  }

  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }

}
