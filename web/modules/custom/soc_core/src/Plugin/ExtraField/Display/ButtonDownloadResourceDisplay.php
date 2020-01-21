<?php

namespace Drupal\soc_core\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;


/**
 * Button Download Resource Extra field Display.
 *
 * @ExtraFieldDisplay(
 *   id = "button_download_resource",
 *   label = @Translation("Button Download Resource"),
 *   bundles = {
 *     "node.resource",
 *   }
 * )
 */
class ButtonDownloadResourceDisplay extends ExtraFieldDisplayBase {

  public function view(ContentEntityInterface $entity) {
    $output = [
      '#type' => 'html_tag',
      '#value' => 'ButtonDownloadResourceDisplay',
      '#tag' => 'h1',
    ];
    return $output;
  }


}
