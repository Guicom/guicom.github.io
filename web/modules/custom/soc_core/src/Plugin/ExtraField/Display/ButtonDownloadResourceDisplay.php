<?php

namespace Drupal\soc_core\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
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

  use StringTranslationTrait;

  public function view(ContentEntityInterface $entity) {


    $url = Url::fromRoute('<current>');


    $label = '';
    $attributes = [];
    if ($entity->get('field_res_downloadable')
        ->getValue()[0]['value'] === '0') {
      // je pose un lien classique sur la page.
      $label = "Lien classique";
      // field field_open_link_in_new_window.
      if ($entity->get('field_open_link_in_new_window')
          ->getValue()[0]['value'] === '1') {
        $attributes = ['target' => '_blank'];
      }
      if ($entity->get('field_res_link_url')->first()->getValue()) {
        $url = Url::fromUri($entity->get('field_res_link_url')
          ->first()
          ->getValue()['value']);
      }


    }
    else {
      // je pose un lien de téléchargement.
      $label = "Lien download";

      var_dump($entity->get('field_res_remote_file_url')->getValue());
      // @todo: chercher le field correspondant.
    }

    $build['examples_link'] = [
      '#title' => $label,
      '#type' => 'link',
      '#url' => $url,
      '#attributes' => $attributes,
    ];
    return $build;
  }


}
