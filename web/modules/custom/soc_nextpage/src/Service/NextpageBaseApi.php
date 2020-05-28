<?php

namespace Drupal\soc_nextpage\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\TempStore\SharedTempStoreFactory;
use Drupal\Core\TempStore\TempStoreException;
use Drupal\soc_core\Service\BaseApi;
use Drupal\soc_nextpage\Exception\InvalidTokenException;

/**
 *
 */
class NextpageBaseApi extends BaseApi {

  const AUTH_URI = '/api/auth';

  /**
   * @var string*/
  public $baseUrl;

  /**
   * @var string*/
  protected $userName;

  /**
   * @var string*/
  protected $password;

  /**
   * @var string*/
  protected $contextId;

  /**
   * @var string*/
  protected $languageId;

  /**
   * @var string*/
  protected $apiToken;

  /**
   * @var string*/
  protected $apiTokenExpiration;

  /**
   * @var \Drupal\Core\TempStore\SharedTempStore*/
  protected $tempStore;

  protected $extIds;

  protected $authStatus;

  /**
   * @var \Drupal\Core\Config\Config
   */
  private $config;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channelFactory
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   * @param \Drupal\Core\TempStore\SharedTempStoreFactory $sharedTempStoreFactory
   */
  public function __construct(LoggerChannelFactoryInterface $channelFactory,
                              ConfigFactoryInterface $configFactory,
                              SharedTempStoreFactory $sharedTempStoreFactory) {
    parent::__construct($channelFactory);

    $this->logger = $channelFactory->get('soc_nextpage');
    $this->tempStore = $sharedTempStoreFactory->get('soc_nextpage');

    $config = $configFactory->getEditable('soc_nextpage.nextpage_ws');
    $this->config = $config;

    $baseUrl = $config->get('base_url') ?? 'https://preprod-socomecweb-api.nextpage.fr/com/';
    $user = $config->get('username') ?? '';
    $password = $config->get('password') ?? '';
    $contextId = $config->get('context_id') ?? '1';
    $languageId = $config->get('language_id') ?? '1';
    $endpoints = [
      'token' => $config->get('endpoint_token') ?? Settings::get('endpoint_token'),
      'dicocarac' => $config->get('endpoint_dicocarac') ?? Settings::get('endpoint_dicocarac'),
      'elementsandlinks' => $config->get('endpoint_elementsandlinks') ?? Settings::get('endpoint_elementsandlinks'),
      'descendantsandlinks' => $config->get('endpoint_descendantsandlinks') ?? Settings::get('endpoint_descendantsandlinks'),
      'elementsbychartemplate' => $config->get('endpoint_elementsbychartemplate') ?? Settings::get('endpoint_elementsbychartemplate'),
    ];
    $extIds = $config->get('channel_extid') ?? Settings::get('channel_extid');
    $authStatus = $config->get('auth_status') ?? 0;

    $this->setBaseUrl($baseUrl);
    $this->setUserName($user);
    $this->setPassword($password);
    $this->setContextId($contextId);
    $this->setLanguageId($languageId);
    $this->setEndpoints($endpoints);
    $this->setExtIds($extIds);
    $this->setAuthStatusxt($authStatus);

  }

  /**
   * @param string $apiToken
   *
   * @throws \Exception
   */
  public function setApiToken(object $apiToken): void {
    $token = ucfirst($apiToken->token_type) . ' ' . $apiToken->access_token;
    $this->apiToken = $token;
    try {
      $this->tempStore->set('api_token', $token);
      $dateTime = new \DateTime();
      $timestamp = $dateTime->getTimestamp();
      $expiration = $timestamp + $apiToken->expires_in;
      $this->setApiTokenExpiration($expiration);
    }
    catch (TempStoreException $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
      throw new TempStoreException($e->getMessage(), 1);
    }
  }

  /**
   * @return int
   */
  public function getApiTokenExpiration(): int {
    return $this->apiTokenExpiration ?? $this->tempStore->get('api_token_expiration');
  }

  /**
   * @param int $apiTokenExpiration
   */
  public function setApiTokenExpiration(int $apiTokenExpiration): void {
    $this->apiTokenExpiration = $apiTokenExpiration;
    try {
      $this->tempStore->set('api_token_expiration', $apiTokenExpiration);
    }
    catch (TempStoreException $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
      throw new TempStoreException($e->getMessage(), 1);
    }
  }

  /**
   * @inheritdoc
   */
  public function prepareCall($params = NULL,
                              $method = 'POST',
                              $format = 'json',
                              $auth = TRUE) {
    $handle = parent::prepareCall($params, $method, $format, $auth);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT_MS, $this->config->get('CURLOPT_CONNECTTIMEOUT_MS'));
    curl_setopt($handle, CURLOPT_TIMEOUT_MS, $this->config->get('CURLOPT_TIMEOUT_MS'));
    // If request needs authentication.
    if ($auth === TRUE) {
      $token = '';
      // If there is no token, generate one.
      if (!strlen($this->getApiToken())) {
        try {
          $token = $this->generateApiToken();
        }
        catch (InvalidTokenException $e) {
          \Drupal::logger('soc_nextpage')->error($e->getMessage());
          throw new InvalidTokenException($e->getMessage(), 1);
        }
      }
      else {
        // If token is expired, generate one.
        if (time() > $this->getApiTokenExpiration()) {
          try {
            $token = $this->generateApiToken();
          }
          catch (InvalidTokenException $e) {
            \Drupal::logger('soc_nextpage')->error($e->getMessage());
            throw new InvalidTokenException($e->getMessage(), 1);
          }
        }
        // Everything is ok, use the current token.
        else {
          $token = $this->getApiToken();
        }
      }
      if (strlen($token)) {
        $headers[] = 'Authorization: ' . $token;
        $this->setHeaders($headers);
      }
    }
    return $handle;
  }

  /**
   * Get a nextPage token.
   *
   * @return object
   *
   * @throws \Drupal\soc_nextpage\Exception\InvalidTokenException
   */
  public function generateApiToken(): object {
    $params = [
      'body' => [
        'username' => $this->getUserName(),
        'password' => $this->getPassword(),
        'grant_type' => 'password',
      ],
    ];
    if (!$token = parent::call(self::AUTH_URI, $params, 'POST', 'x-www-form-urlencoded', FALSE)) {
      throw new InvalidTokenException('Unable to generate valid token.');
    }
    $this->setApiToken($token);
    return $token;
  }

  /**
   * @return string
   */
  public function getBaseUrl(): string {
    return $this->baseUrl;
  }

  /**
   * @param string $baseUrl
   */
  public function setBaseUrl(string $baseUrl): void {
    $this->baseUrl = $baseUrl;
  }

  /**
   * @return string
   */
  public function getUserName(): string {
    return $this->userName;
  }

  /**
   * @param string $userName
   */
  public function setUserName(string $userName): void {
    $this->userName = $userName;
  }

  /**
   * @return string
   */
  public function getPassword(): string {
    return $this->password;
  }

  /**
   * @param string $password
   */
  public function setPassword(string $password): void {
    $this->password = $password;
  }

  /**
   * Get stored API token.
   *
   * @return string|null
   */
  public function getApiToken() {
    return $this->apiToken ?? $this->tempStore->get('api_token');
  }

  /**
   * @return string
   */
  public function getContextId(): int {
    return $this->contextId;
  }

  /**
   * @param string $contextId
   */
  public function setContextId(string $contextId): void {
    $this->contextId = $contextId;
  }

  /**
   * @return string
   */
  public function getLanguageId(): string {
    return $this->languageId;
  }

  /**
   * @param string $languageId
   */
  public function setLanguageId(string $languageId): void {
    $this->languageId = $languageId;
  }

  /**
   * @return string
   */
  public function getExtIds() {
    return $this->extIds;
  }

  /**
   * @param string $extIDs
   */
  public function setExtIds(string $extIds) {
    $this->extIds = $extIds;
  }

  /**
   * @return string
   */
  public function getAuthStatus() {
    return $this->authStatus;
  }

  /**
   * @param int $authStatus
   */
  public function setAuthStatusxt(int $authStatus) {
    $this->authStatus = $authStatus;
  }

}
