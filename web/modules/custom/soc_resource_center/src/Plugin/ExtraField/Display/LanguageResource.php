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
 *   id = "language_resource",
 *   label = @Translation("language resource"),
 *   bundles = {
 *     "node.resource",
 *   }
 * )
 */
class LanguageResource extends ExtraFieldDisplayFormattedBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t('Language resource');
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
    $langcode = $entity->get("langcode");
    if (!empty($langcode->getValue()[0]['value'])) {
      return [
        ['#markup' => $langcode->getValue()[0]['value']]
      ];
    }

    return FALSE;
  }

}
