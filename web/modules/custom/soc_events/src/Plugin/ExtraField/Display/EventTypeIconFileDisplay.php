<?php


namespace Drupal\soc_events\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;
use Drupal\media\Entity\Media;


/**
 * Extra field with formatted output.
 *
 * @ExtraFieldDisplay(
 *   id = "event_type_icon_file",
 *   label = @Translation("Event Type Icon File"),
 *   bundles = {
 *     "node.event",
 *   }
 * )
 */
class EventTypeIconFileDisplay extends ExtraFieldDisplayFormattedBase
{

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel()
  {
    return $this->t('Event Type Icon File');
  }

  /**
   * {@inheritdoc}
   */
  public function getLabelDisplay()
  {
    return 'hidden';
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(ContentEntityInterface $entity): array
  {

    $url = NULL;
    $elements = [];
    try {
      $field_event_picture = $entity->get('field_event_picture')->first();
      if (!empty($field_event_picture) && !empty($field_event_picture->getValue())) {
        return $elements;
      }
      $target_id = $entity->get('field_event_type')->first()->getValue()['target_id'];
      /** @var \Drupal\taxonomy\Entity\Term $term */
      $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($target_id);
      if ($term->get('field_event_type_icon')->count() === 0) {
        return $elements;
      }
      $media_id = $term->get('field_event_type_icon')->first()->getvalue()['target_id'];
      /** @var \Drupal\media\Entity\Media $media */
      $media = Media::load($media_id);
      if ($media->get('field_media_image')->count() === 0) {
        return $elements;
      }
      if ($file_id = $media->get('field_media_image')->first()->getValue()['target_id']) {
        $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
        if (!empty ($file->getFileUri())) {
          $build = [
            '#theme' => 'image',
            '#uri' => $file->getFileUri(),
          ];
          return [
            ['#markup' => render($build)],
          ];
        }
      }
    } catch (\Exception $e) {
      $url = NULL;
      \Drupal::logger('soc_events')->error($e->getMessage());
    }
    return $elements;
  }


}
