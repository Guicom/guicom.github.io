<?php

namespace Drupal\soc_resource_center\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;

/**
 * Extra field with formatted output.
 *
 * @ExtraFieldDisplay(
 *   id = "file_size_resource",
 *   label = @Translation("File Size resource"),
 *   bundles = {
 *     "node.resource",
 *   }
 * )
 */
class FileSizeResource extends ExtraFieldDisplayFormattedBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t('File Size resource');
  }

  /**
   * {@inheritdoc}
   */
  public function getLabelDisplay() {
    return 'hidden';
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(ContentEntityInterface $entity) {
    $field_res_downloadable = $entity->get('field_res_downloadable');
    if (!empty($field_res_downloadable) && $field_res_downloadable->getValue()[0]['value'] === '1') {
      $field_res_remote_file_url = $entity->get('field_res_remote_file_url');
      if (!empty($field_res_remote_file_url)) {
        if (!empty($field_res_remote_file_url->first())) {
          if ($id = $field_res_remote_file_url->first()->getValue()['target_id']) {
            /* @var \Drupal\file\FileInterface $file */
            $file = \Drupal::entityTypeManager()->getStorage('file')->load($id);
            $size= $file->getSize();
            return [
              ['#markup' => '<span class="ressource-size-file">'.format_size($size).'</span>'],
            ];
          }
        }
      }
    }
    return FALSE;
  }

}
