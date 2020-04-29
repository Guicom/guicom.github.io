<?php

namespace Drupal\soc_traceparts\Service;


use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\soc_core\Service\BaseApi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

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

  /** @var \GuzzleHttp\Client $httpClient */
  protected $httpClient;

  /** @var array $endpoints */
  protected $endpoints;

  /** @var string $apiKey */
  protected $apiKey;

  /**
   * Constructs a new TracepartsApi object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channel_factory
   * @param \GuzzleHttp\Client $http_client
   */
  public function __construct(ConfigFactoryInterface $config_factory,
                              LoggerChannelFactoryInterface $channel_factory,
                              Client $http_client) {
    parent::__construct($channel_factory);
    $this->logger = $channel_factory->get('soc_traceparts');
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
    $this->baseUrl = 'http://ws.tracepartsonline.net/tpowebservices/';
    $this->endpoints = [
      'CadDataAvailability' => 'CADdataAvailability',
      'CheckLogin' => 'CheckLogin',
      'UserRegistration' => 'UserRegistration',
      'DownloadCadPath' => 'DownloadCADPath',
    ];
    $this->apiKey = '4GusSVAXU968or';
  }

  /**
   * Get CAD data availability for a given part number.
   *
   * @param string $part_number
   *
   * @return array
   */
  public function getCadDataAvailability(string $part_number): array {
    $uri = $this->endpoints['CadDataAvailability'];
    $params = [
      'ApiKey' => $this->getApiKey(),
      'Language' => 'en',
      'Format' => 'json',
      'ClassificationID' => 'SOCOMEC',
      'PartNumber' => $part_number,
    ];
    if ($results = $this->call($uri, $params, 'GET', 'json', FALSE)) {
      if (sizeof((array) $results)) {
        return (array) $results;
      }
    }
    return [];
  }

  /**
   * Check if user accounts exists.
   *
   * @param string $user_email
   *
   * @return bool
   */
  public function checkLogin(string $user_email): bool {
    $uri = $this->endpoints['CheckLogin'];
    $params = [
      'ApiKey' => $this->getApiKey(),
      'Format' => 'json',
      'UserEmail' => $user_email,
    ];
    if ($results = $this->call($uri, $params, 'GET', 'json', FALSE)) {
      if (sizeof((array) $results)) {
        if ($results->registered == 'true') {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * Register a new user account.
   *
   * @param array $user_data
   *
   * @return bool
   */
  public function userRegistration(array $user_data): bool {
    $uri = $this->endpoints['UserRegistration'];
    $params = [
      'ApiKey' => $this->getApiKey(),
      'Format' => 'json',
      'UserEmail' => $user_data['email'],
      'company' => $user_data['company'],
      'country' => $user_data['country'],
      'zipcode' => $user_data['zipcode'],
    ];
    if ($results = $this->call($uri, $params, 'GET', 'json', FALSE)) {
      if (sizeof((array) $results)) {
        if ($results->registered == 'true') {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * @param array $data
   *
   * @return array
   */
  public function downloadCadPath(array $data): array {
    $uri = $this->endpoints['DownloadCadPath'];
    $params = [
      'ApiKey' => $this->getApiKey(),
      'Format' => 'json',
      'UserEmail' => $data['email'],
      'ClassificationID' => 'SOCOMEC',
      'PartNumber' => $data['part_number'],
      'CADFormatID' => $data['format_id'],
      'Version' => 2,
    ];
    if ($results = $this->call($uri, $params, 'GET', 'json', FALSE)) {
      if (sizeof((array) $results)) {
        if (sizeof((array) $results->globalInfo) && sizeof($results->filesPath)) {
          return (array) $results;
        }
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

  /**
   * Proceed an API call.
   *
   * @param string $uri
   * @param array|null $params
   * @param string $method
   * @param string $format
   * @param bool $auth
   * @param int $max_tries
   *
   * @return mixed
   */
  protected function call($uri, $params = NULL, $method = 'POST', $format = 'json',
                          $auth = TRUE, $max_tries = 5) {
    $response = [];
    $url = $this->getBaseUrl() . $uri . '?' . http_build_query($params);
    try {
      switch ($method) {
        case 'GET':
          $request = $this->httpClient->get($url);
          break;
        default:
          $request = $this->httpClient->post($url);
          break;
      }
      $response = $request->getBody()->getContents();
      switch ($format) {
        case 'json':
          $response = json_decode($response);
          break;
        default:
          break;
      }
    }
    catch (ClientException $e) {
    }
    catch (RequestException $e) {
      $this->logger->warning($e->getMessage());
    }
    return $response;
  }

}
