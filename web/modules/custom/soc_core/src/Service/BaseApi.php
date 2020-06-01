<?php

namespace Drupal\soc_core\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class BaseApi.
 *
 * @package Drupal\soc_core\Service
 */
class BaseApi {

  public $baseUrl;

  protected $user;

  protected $password;

  protected $headers;

  /**
   * @var array*/
  protected $endpoints;

  /**
   * The soc_core logging channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channelFactory
   */
  public function __construct(LoggerChannelFactoryInterface $channelFactory) {
    $this->logger = $channelFactory->get('soc_core');
  }

  /**
   * @return mixed
   */
  public function getBaseUrl() {
    return $this->baseUrl;
  }

  /**
   * @return array
   */
  public function getEndpoints(): array {
    return $this->endpoints;
  }

  /**
   * @param array $endpoints
   */
  public function setEndpoints(array $endpoints) {
    $this->endpoints = $endpoints;
  }

  /**
   * @param mixed $headers
   */
  public function setHeaders($headers): void {
    $this->headers = $headers;
  }

  /**
   * @return mixed
   */
  public function getHeaders() {
    return $this->headers;
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
  protected function call($uri,
  $params = NULL,
  $method = 'POST',
  $format = 'json',
  $auth = TRUE,
  $max_tries = 5) {
    $handle = $this->prepareCall($params, $method, $format, $auth);

    $url = $this->getBaseUrl() . $uri;
    curl_setopt($handle, CURLOPT_URL, $url);
    // Upload bug tips. We sent the header and then the body.
    $headers = $this->getHeaders();
    $headers[] = 'Content-Type: application/' . $format;
    // $headers[] = "Expect:";
    $this->setHeaders($headers);

    if ($format === 'json') {
      $params['_format'] = 'json';
    }

    $tries = 0;
    do {
      switch ($method) {
        case 'POST':
          curl_setopt($handle, CURLOPT_POST, TRUE);
          if ($format === 'json') {
            if (is_array($params['body'])) {
              $body = json_encode($params['body']);
            }
            elseif (is_string($params['body'])) {
              $body = $params['body'];
            }
            $curl = curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
            $headers = $this->getHeaders();
            $headers[] = 'Content-Length: ' . strlen($body);
            $this->setHeaders($headers);
          }
          elseif ($format == 'x-www-form-urlencoded') {
            $body = http_build_query($params["body"]);
            $curl = curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
            // $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $headers[] = 'Content-Length: ' . strlen($body);
            $this->setHeaders($headers);
          }
          else {
            $curl = curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
          }
          $curl = curl_setopt($handle, CURLOPT_HTTPHEADER, $this->getHeaders());
          break;

        case 'PUT':
          $curl = curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
          $curl = curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
          $curl = curl_setopt($handle, CURLOPT_HTTPHEADER, $this->getHeaders());
          break;

        case 'DELETE':
          $curl = curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
          $curl = curl_setopt($handle, CURLOPT_HTTPHEADER, $this->getHeaders());
          break;

        case 'GET':
          if ($format === 'json') {
            $body = '';
            if (isset($params['body']) && is_array($params['body'])) {
              $body = json_encode($params['body']);
            }
            elseif (isset($params['body']) && is_string($params['body'])) {
              $body = $params['body'];
            }
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
          }
          else {
            curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
          }
          $curl = curl_setopt($handle, CURLOPT_HTTPHEADER, $this->getHeaders());
          break;

        default:
          break;
      }

      $res = curl_exec($handle);
      $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
      if ($code === 200) {
        break;
      }
      $tries++;
      sleep(1);
    } while ($tries < $max_tries);

    if (FALSE === $res) {
      $message = "$method $url failed (cURL code " . curl_errno($handle) . "): "
        . htmlspecialchars(curl_error($handle));
      $this->logger->error($message);
      throw new \Exception($message, 1);
    }
    if (NULL === $res) {
      $message = "$method $url failed: " . error_get_last();
      $this->logger->error($message);
      throw new \Exception($message, 1);
    }
    if (401 === $code) {
      $message = "$method $url failed: " . t('Wrong credentials');
      $this->logger->error($message);
      throw new \Exception($message, 1);
    }
    if (200 != $code) {
      $message = "$method $url failed: " . t('Error in API call, please check your configuration');
      throw new \Exception($message, 1);
    }

    curl_close($handle);

    switch ($format) {
      case 'json':
      case 'x-www-form-urlencoded':
        $res = json_decode($res);
        // Reset header.
        $this->setHeaders([]);
        break;

      case 'xml':
        $res = simplexml_load_string($res);
        break;

      default:
        // We do not implement default as in this case we return the raw data.
        break;
    }
    if (NULL === $res) {
      $message = "Failed to decode response as JSON.";
      throw new \Exception($message, 1);
    }
    return $res;
  }

  /**
   * Prepare an API call using cURL.
   *
   * @param array|null $params
   * @param string $method
   * @param string $format
   * @param bool $auth
   *
   * @return mixed
   */
  protected function prepareCall($params = NULL,
  $method = 'POST',
  $format = 'json',
                                 $auth = TRUE) {
    $headers = [
      "Accept: application/$format",
      "Content-Type: application/$format",
    ];

    $handle = curl_init();
    if ($auth && $this->user && $this->password) {
      // HTTP basic access authentication.
      curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($handle, CURLOPT_USERPWD, $this->user . ':' . $this->password);
    }
    curl_setopt($handle, CURLOPT_VERBOSE, FALSE);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($handle, CURLOPT_SAFE_UPLOAD, TRUE);
    return $handle;
  }

}
