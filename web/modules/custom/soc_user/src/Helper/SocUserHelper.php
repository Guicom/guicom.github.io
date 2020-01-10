<?php


namespace Drupal\soc_user\Helper;


class SocUserHelper {

  /**
   *  Verification of it is necessary to deactivate the super admin account.
   *
   * @return bool
   */
  public static function disableSuperAdminAccount() {
    return getenv('DISABLE_SUPER_ADMIN_ACCOUNT') == 1 ? TRUE : FALSE;
  }
}