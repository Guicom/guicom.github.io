<?php

namespace Drupal\soc_traceparts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\soc_traceparts\Service\Manager\TracepartsViewerManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Node cached block that displays the Tracepart viewer of a reference.
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
      TracepartsViewerManager $downloads_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->viewerManager = $downloads_manager;
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
    /** @var $node \Drupal\node\NodeInterface $node */
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      if ($partNumber = $node->get('field_reference_ref')->value) {
        if ($this->viewerManager->getViewerAvailability($partNumber)) {
          $baseUrl = 'https://www.traceparts.com/els/socomec/en/api/viewer/3d';
          $params = [
            'SupplierID' => 'SOCOMEC',
            'PartNumber' => $partNumber,
            'SetBackgroundColor' => '0xF0EFEF',
            'DisplayLogo' => 'none',
            'EnableMirrorEffect' => 'false',
          ];
          $viewerUrl = Url::fromUri($baseUrl, [
            'query' => $params,
          ]);
          $build['node_id'] = [
            '#type' => 'inline_template',
            '#template' => '<iframe src="' . $viewerUrl->toString() . '">'
              . $this->t('Loading...') . '</iframe>',
          ];
        }
      }
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
