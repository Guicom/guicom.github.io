<?php

namespace Drupal\soc_nextpage\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\TempStore\SharedTempStoreFactory;
use Drupal\Core\TempStore\TempStoreException;
use Drupal\soc_core\Service\BaseApi;
use Drupal\soc_nextpage\Exception\InvalidTokenException;

class NextpageBaseApi extends BaseApi {

  const AUTH_URI = '/api/auth';

  /** @var string $baseUrl */
  public $baseUrl;

  /** @var string $config */
  protected $userName;

  /** @var string $password */
  protected $password;

  /** @var string $contextId */
  protected $contextId;

  /** @var string $languageId */
  protected $languageId;

  /** @var string $apiToken */
  protected $apiToken;

  /** @var string $apiTokenExpiration */
  protected $apiTokenExpiration;

  /** @var \Drupal\Core\TempStore\SharedTempStore $tempStore */
  protected $tempStore;

  protected $extIds;

  protected $authStatus;

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

    $baseUrl = $config->get('base_url') ?? Settings::get('nextpage_base_url');
    $user = $config->get('username') ?? Settings::get('nextpage_username');
    $password = $config->get('password') ?? Settings::get('nextpage_password');
    $contextId = $config->get('context_id') ?? '1';
    $languageId = $config->get('language_id') ?? '1';
    $endpoints = [
      'token' => $config->get('endpoint_token') ?? Settings::get('endpoint_token'),
      'dicocarac' => $config->get('endpoint_dicocarac') ?? Settings::get('endpoint_dicocarac'),
      'elementsandlinks' => $config->get('endpoint_elementsandlinks') ?? Settings::get('endpoint_elementsandlinks'),
      'descendantsandlinks' => $config->get('endpoint_descendantsandlinks') ?? Settings::get('endpoint_descendantsandlinks'),
      'elementsbychartemplate' => $config->get('endpoint_elementsbychartemplate') ?? Settings::get('endpoint_elementsbychartemplate'),
    ];
    $extIds = $config->get('channel_extid') ??  Settings::get('channel_extid');
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
  public function setApiToken(string $apiToken): void {
    $this->apiToken = $apiToken;
    try {
      $this->tempStore->set('api_token', $apiToken);
      $dateTime = new \DateTimeImmutable();
      $expiration = $dateTime->modify('+2 hours');
      $this->setApiTokenExpiration($expiration->getTimestamp());
    } catch (TempStoreException $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
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
    } catch (TempStoreException $e) {
      \Drupal::logger('soc_nextpage')->error($e->getMessage());
    }
  }

  /**
   * @inheritdoc
   */
  public function prepareCall($params = NULL,
                              $method = 'POST',
                              $format = 'json',
                              $auth = TRUE) {
    $handle = Parent::prepareCall($params, $method, $format, $auth);
    // if request needs authentication
    if ($auth === TRUE) {
      $token = '';
      // if there is no token, generate one
      if (!strlen($this->getApiToken())) {
        try {
          $token = $this->generateApiToken();
        } catch (InvalidTokenException $e) {
          \Drupal::logger('soc_nextpage')->error($e->getMessage());
        }
      }
      else {
        // if token is expired, generate one
        if (time() > $this->getApiTokenExpiration()) {
          try {
            $token = $this->generateApiToken();
          } catch (InvalidTokenException $e) {
            \Drupal::logger('soc_nextpage')->error($e->getMessage());
          }
        }
        // everything is ok, use the current token
        else {
          $token = $this->getApiToken();
        }
      }
      if (strlen($token)) {
        $headers = $this->getHeaders();
        $headers[] = 'ApiToken: ' . $token;
        $this->setHeaders($headers);
      }
    }
    return $handle;
  }

  /**
   * Get a nextPage token.
   *
   * @return string
   * @throws \Drupal\soc_nextpage\Exception\InvalidTokenException
   */
  public function generateApiToken(): string {
    $params = [
      'body' => [
        'Username' => $this->getUserName(),
        'Password' => $this->getPassword(),
      ],
    ];
    if (!$token = parent::call(self::AUTH_URI, $params, 'POST', 'json', FALSE)) {
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
