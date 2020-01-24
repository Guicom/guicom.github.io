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
 *   id = "file_type_resource",
 *   label = @Translation("File Type resource"),
 *   bundles = {
 *     "node.resource",
 *   }
 * )
 */
class FileTypeResource extends ExtraFieldDisplayFormattedBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t('File Type resource');
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
        $id = $field_res_remote_file_url->first()->getValue()['target_id'];
      }
      if (!empty($field_res_remote_file_url)) {
        /* @var \Drupal\file\FileInterface $file */
        $file = \Drupal::entityTypeManager()->getStorage('file')->load($id);
        $mimeType = $file->getMimeType();
        $type = str_replace('application/', '', $mimeType);
        return [
          ['#markup' => '<span class="ressource-type-file">'.$type.'</span>'],
        ];
      }
    }
    return FALSE;
  }

}
