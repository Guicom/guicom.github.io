<?php

namespace Drupal\soc_resource_center\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;
use Drupal\Component\Utility\Xss;

/**
 * Extra field with formatted output.
 *
 * @ExtraFieldDisplay(
 *   id = "resource_description",
 *   label = @Translation("Resource description"),
 *   bundles = {
 *     "node.resource",
 *   }
 * )
 */
class ResourceDescription extends ExtraFieldDisplayFormattedBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t('Resource description');
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
    $field_description = $entity->get('field_description')->value;
    if (strcmp($field_description, '') == 0) {
      $field_description = $entity->get('field_res_original_description')->value;
    }
    if (strlen($field_description) > 120) {
      $field_description = substr($field_description, 0, 120) . '...';
    }
    return [
      ['#markup' => Xss::filter($field_description)],
    ];
  }

}
