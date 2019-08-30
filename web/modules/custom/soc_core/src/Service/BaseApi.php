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

  protected $client;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->client = \Drupal::httpClient();
  }

  /**
   * Prepare the call
   *
   * @param array|null $params
   * @param string $format
   * @param bool $auth
   *
   * @return mixed
   */
  protected function prepareCall($params = NULL, $format = 'json', $auth = TRUE) {
    if (array_key_exists('json', $params)) {
      $requestParameters = $params;
    }
    else {
      $requestParameters = [
        'form_params' => $params,
        'verify' => FALSE,
        'headers' => [
          'Accept: application/' . $format,
          'Content-type' => 'application/x-www-form-urlencoded',
        ]
      ];
    }

    if (TRUE === $auth) {
      $requestParameters['auth'] = [$this->user, $this->password];
    }

    return $requestParameters;
  }

  /**
   * Proceed an API call.
   *
   * @param string $url
   * @param array|null $params
   * @param string $method
   * @param string $format
   * @param bool $auth
   * @param integer $maxTries
   *  Number of retry in case of failure.
   *
   * @return mixed
   */
  protected function call($url, $params = NULL, $method = 'POST', $format = 'json', $auth = TRUE, $maxTries = 5) {

    $requestParameters = $this->prepareCall($params, $format, $auth);

    $tries = 0;
    do {
      switch ($method) {
        case 'POST':
          $response = $this->client->post($url, $requestParameters);
          break;

        default:
          $response = $this->client->get($url, $requestParameters);
          break;
      }
      if ($response->getStatusCode() === 200) {
        break;
      }
      $tries++;
      sleep(1);
    } while ($tries < $maxTries);

    $res = $response->getBody();

    if (!$res) {
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
      $message = "failed to decode $res as json";
      \Drupal::logger('soc_core')->error($message);
      return false;
    }

    return $res;
  }

  /**
   * Proceed an API call.
   *
   * @param string $url
   * @param array|null $params
   * @param string $method
   * @param string $format
   * @param bool $auth
   *
   * @return mixed
   */
  protected function callCurl($url, $params = NULL, $method = 'POST', $format = 'json', $auth = TRUE, $maxTries = 5) {
    $handle = $this->curlPrepare($params, $method, $format, $auth);
    curl_setopt($handle, CURLOPT_URL, $url);
    // Upload bug tips. We sent the header and then the body.
    curl_setopt($handle, CURLOPT_HTTPHEADER, ["Expect:"]);

    $tries = 0;
    do {
      switch ($method) {
        case 'POST':
          curl_setopt($handle, CURLOPT_POST, TRUE);
          curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
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

      $res = curl_exec($handle);
      $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
      if ($code === 200) {
        break;
      }
      $tries++;
      sleep(1);
    } while ($tries < $maxTries);

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
      $message = "failed to decode $res as json";
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
  protected function curlPrepare($params = NULL, $method = 'POST', $format = 'json', $auth = TRUE) {

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
