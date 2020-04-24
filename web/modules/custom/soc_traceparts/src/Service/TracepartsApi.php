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

  /** @var array $endpoints */
  protected $endpoints;

  /** @var string $apiKey */
  protected $apiKey;

  /**
   * Constructs a new TracepartsApi object.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channelFactory
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(LoggerChannelFactoryInterface $channelFactory,
                              ConfigFactoryInterface $config_factory) {
    parent::__construct($channelFactory);
    $this->logger = $channelFactory->get('soc_traceparts');
    $this->configFactory = $config_factory;
    $this->baseUrl = 'http://ws.tracepartsonline.net/tpowebservices/';
    $this->endpoints = [
      'CadDataAvailability' => 'CADdataAvailability',
    ];
    $this->apiKey = '4GusSVAXU968or';
  }

  /**
   * @param string $part_number
   *
   * @return array
   */
  public function getCadDataAvailability(string $part_number): array {
    $uri = $this->getBaseUrl() . $this->endpoints['CadDataAvailability'];
    $params = [
      'ApiKey' => $this->getApiKey(),
      'Language' => 'en',
      'Format' => 'json',
      'ClassificationID' => 'SOCOMEC',
      'PartNumber' => $part_number,
    ];
    if ($results = $this->call($uri, $params, 'GET', 'json', FALSE)) {
      if (sizeof($results)) {
        return $results;
      }
    }
    return [];
  }

  /**
   * @return string
   */
  public function getApiKey(): string {
    return $this->apiKey;
  }

}
