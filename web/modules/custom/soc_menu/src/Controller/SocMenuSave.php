<?php
/**
 * @file
 * Contains \Drupal\soc_menu\Controller\SocMenuSave.
 */

namespace Drupal\soc_menu\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\we_megamenu\WeMegaMenuBuilder;

/**
 * An example controller.
 */
class SocMenuSave extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function saveConfig() {
    if (isset($_POST['action']) && $_POST['action'] == 'save') {
      $data_config = $_POST['data_config'];
      $theme = $_POST['theme'];
      $menu_name = $_POST['menu_name'];
      WeMegaMenuBuilder::saveConfig($menu_name, $theme, $data_config);
      we_megamenu_flush_render_cache();
      Drupal::service('we_megamenu_deploy.exporter')->exportMenus($menu_name);
    }
    exit;
  }
}
