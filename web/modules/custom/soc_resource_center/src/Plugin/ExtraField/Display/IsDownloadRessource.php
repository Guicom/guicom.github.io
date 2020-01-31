<?php

namespace Drupal\soc_resource_center\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;

/**
 * Extra field with formatted output.
 *
 * @ExtraFieldDisplay(
 *   id = "is_download_ressource",
 *   label = @Translation("Download text"),
 *   bundles = {
 *     "node.resource",
 *   }
 * )
 */
class IsDownloadRessource extends ExtraFieldDisplayFormattedBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t('Download text');
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
      return [
        ['#markup' => t('Download')],
      ];
    }
    return FALSE;
  }

}
