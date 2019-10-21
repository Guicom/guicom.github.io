<?php
/**
 * @file Provides a service to structure an API-based service
 */

namespace Drupal\soc_core\Service;

/**
 * Class BaseApi
 *
 * @package Drupal\soc_core\Service
 */
class BaseApi {

  public $baseUrl;

  protected $user;
  protected $password;

  protected $headers;

  /**
   * Constructor.
   */
  public function __construct() {
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
  protected function call($uri, $params = NULL, $method = 'POST', $format = 'json',
                          $auth = TRUE, $max_tries = 5) {
    $handle = $this->prepareCall($params, $method, $format, $auth);
    // Add leading slash if omitted
    if (substr($uri, 0, 1) !== '/') {
      $uri = '/' . $uri;
    }
    $url = $this->getBaseUrl() . $uri;
      curl_setopt($handle, CURLOPT_URL, $url);
    // Upload bug tips. We sent the header and then the body.
    $headers = $this->getHeaders();
    $headers[] = "Expect:";
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
            curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
            $headers = $this->getHeaders();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($body);
            $this->setHeaders($headers);
          }
          else {
            curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
          }
          break;
        case 'PUT':
          curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
          curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
          break;
        case 'DELETE':
          curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
          break;
        case 'GET':
        default:
          break;
      }

      curl_setopt($handle, CURLOPT_HTTPHEADER, $this->getHeaders());

      $res = curl_exec($handle);
      $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
      if ($code === 200) {
        break;
      }
      $tries++;
      sleep(1);
    } while ($tries < $max_tries);

    curl_close($handle);

    if (FALSE === $res) {
      $message = "$method $url failed (cURL code " . curl_errno($handle) . "): "
        . htmlspecialchars(curl_error($handle));
      \Drupal::logger('soc_core')->error($message);
      return false;
    }
    if (NULL === $res) {
      $message = "$method $url failed: " . error_get_last();
      \Drupal::logger('soc_core')->error($message);
      return false;
    }

    switch ($format) {
      case 'json':
        $res = json_decode($res);
        break;
      case 'xml':
        $res = simplexml_load_string($res);
        break;
      default:
        // We do not implement default as in this case we return the raw data
        break;
    }
    if (null === $res) {
      $message = "Failed to decode response as JSON.";
      \Drupal::logger('soc_core')->error($message);
      return false;
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
  protected function prepareCall($params = NULL, $method = 'POST', $format = 'json',
                                 $auth = TRUE) {
    $headers = [
      "Accept: application/$format",
      "Content-Type: application/$format",
    ];

    $handle = curl_init();
    if ($auth && $this->user && $this->password) {
      // HTTP basic access authentication
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
