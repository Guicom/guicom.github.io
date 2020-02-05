<?php

namespace Drupal\soc_resource_center\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;


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
class ButtonDownloadResourceDisplay extends ExtraFieldDisplayFormattedBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t('Download resource');
  }

  /**
   * {@inheritdoc}
   */
  public function getLabelDisplay() {
    return 'hidden';
  }

  /**
   * Returns the renderable array of the field item(s).
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The field's host entity.
   *
   * @return array
   *   A renderable array of field elements. If this contains children, the
   *   field output will be rendered as a multiple value field with each child
   *   as a field item.
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public function viewElements(ContentEntityInterface $entity) {
    $url = NULL;
    $label = NULL;
    $attributes['class'] = ['resource-download'];
    $label = $this->t('Download');
    if ($entity->get('field_res_downloadable')->getValue()[0]['value'] === '0') {
      if ($entity->get('field_open_link_in_new_window')->getValue()[0]['value'] === '1') {
        $attributes['target'] = '_blank';
      }
      $field_res_link_url = $entity->get('field_res_link_url')->first();
      if (!empty($field_res_link_url) && !empty($field_res_link_url->getValue())) {
        $url = Url::fromUri($entity->get('field_res_link_url')->first()->getValue()['value']);
      }
      $attributes['class'][]='is-link';
    }
    else {
      // je pose un lien de téléchargement.
      if ($id = $entity->get('field_res_remote_file_url')->first()->getValue()['target_id']) {
        try {
          /* @var \Drupal\file\FileInterface $file */
          $file = \Drupal::entityTypeManager()->getStorage('file')->load($id);
          $url = Url::fromUri($file->getFileUri());
          $attributes['download'] = TRUE;
          $attributes['class'][]='is-file';
        } catch (\Exception $e) {
          \Drupal::logger('soc_resource_center')->error($e->getMessage());
        }
      }
    }

    $build['download_link'] = [
      '#title' => $label,
      '#type' => 'link',
      '#url' => $url,
      '#attributes' => $attributes,
    ];
    return [
      ['#markup' => render($build)],
    ];
  }
}
