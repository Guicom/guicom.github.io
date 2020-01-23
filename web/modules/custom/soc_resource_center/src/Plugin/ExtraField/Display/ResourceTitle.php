<?php

namespace Drupal\soc_resource_center\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;

/**
 * Extra field with formatted output.
 *
 * @ExtraFieldDisplay(
 *   id = "resource_title",
 *   label = @Translation("Resource title"),
 *   bundles = {
 *     "node.resource",
 *   }
 * )
 */
class ResourceTitle extends ExtraFieldDisplayFormattedBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t('Resource title');
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
    $title = $entity->get('field_res_title')->value;
    if (strcmp($title, '') == 0) {
      $title = $entity->get('field_res_original_title')->value;
    }
    return [
      ['#markup' => $title],
    ];
  }

}
