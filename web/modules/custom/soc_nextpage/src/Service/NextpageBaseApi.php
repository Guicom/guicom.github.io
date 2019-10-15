<?php

namespace Drupal\soc_nextpage\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
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

  /** @var string $apiToken */
  protected $apiToken;

  /** @var string $apiTokenExpiration */
  protected $apiTokenExpiration;

  /** @var \Drupal\Core\TempStore\SharedTempStore $tempStore */
  protected $tempStore;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   * @param \Drupal\Core\TempStore\SharedTempStoreFactory $sharedTempStoreFactory
   */
  public function __construct(ConfigFactoryInterface $configFactory,
                              SharedTempStoreFactory $sharedTempStoreFactory) {
    parent::__construct();

    $this->tempStore = $sharedTempStoreFactory->get('soc_nextpage');

    $config = $configFactory->getEditable('soc_nextpage.nextpage_ws');

    $baseUrl = $config->get('base_url') ?? Settings::get('nextpage_base_url');
    $user = $config->get('username') ?? Settings::get('nextpage_username');
    $password = $config->get('password') ?? Settings::get('nextpage_password');

    $this->setBaseUrl($baseUrl);
    $this->setUserName($user);
    $this->setPassword($password);
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
   * @return string
   */
  public function getApiToken(): string {
    return $this->apiToken ?? $this->tempStore->get('api_token');
  }

  /**
   * @param string $apiToken
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
      }
      if (strlen($token)) {
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['ApiToken: ' . $token]);
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
    $url = $this->getBaseUrl() . self::AUTH_URI;
    if (!$token = parent::call($url, $params, 'POST', 'json', FALSE)) {
      throw new InvalidTokenException('Unable to generate valid token.');
    }
    $this->setApiToken($token);
    return $token;
  }

}
