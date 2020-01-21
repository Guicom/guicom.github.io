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


    $url = NULL;
    $label = NULL;
    $attributes['class'] = array('btn','btn-primary');
    if ($entity->get('field_res_downloadable')->getValue()[0]['value'] === '0') {
      $label = $this->t('See the link');
      if ($entity->get('field_open_link_in_new_window')->getValue()[0]['value'] === '1') {
        $attributes['target'] = '_blank';
      }
      if ($entity->get('field_res_link_url')->first()->getValue()) {
        $url = Url::fromUri($entity->get('field_res_link_url')->first()->getValue()['value']);
      }
      $attributes['class'][]='is-link';
    }
    else {
      // je pose un lien de téléchargement.
      $label = $this->t('Download the file');
      $id = $entity->get('field_res_remote_file_url')->first()->getValue()['target_id'];
      /* @var \Drupal\file\FileInterface $file */
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($id);
      $url = Url::fromUri($file->getFileUri());
      $attributes['download'] = TRUE;
      $attributes['class'][]='is-file';
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
