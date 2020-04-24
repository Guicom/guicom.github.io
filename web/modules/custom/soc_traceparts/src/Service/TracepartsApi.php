<?php

namespace Drupal\soc_traceparts\Service;


use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\soc_core\Service\BaseApi;

/**
 * Class TracepartsApi.
 */
class TracepartsApi extends BaseApi {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new TracepartsApi object.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channelFactory
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(LoggerChannelFactoryInterface $channelFactory,
                              ConfigFactoryInterface $config_factory) {
    parent::__construct($channelFactory);
    $this->configFactory = $config_factory;
  }

}
