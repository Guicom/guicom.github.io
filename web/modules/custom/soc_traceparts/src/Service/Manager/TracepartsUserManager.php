<?php

namespace Drupal\soc_traceparts\Service\Manager;

use Drupal\soc_traceparts\Service\TracepartsApi;

/**
 * Class TracepartsUserManager.
 */
class TracepartsUserManager {

  private $tracepartsApi;

  /**
   * Constructs a new TracepartsUserManager object.
   *
   * @param \Drupal\soc_traceparts\Service\TracepartsApi $traceparts_api
   */
  public function __construct(TracepartsApi $traceparts_api) {
    $this->tracepartsApi = $traceparts_api;
  }

  /**
   * @param string $user_email
   *
   * @return bool
   */
  public function checkLogin(string $user_email): bool {
    return $this->tracepartsApi->checkLogin($user_email);
  }

  /**
   * Register a new user account.
   *
   * @param array $user_data
   *
   * @return bool
   */
  public function registerUser(array $user_data): bool {
    return $this->tracepartsApi->userRegistration($user_data);
  }

}
