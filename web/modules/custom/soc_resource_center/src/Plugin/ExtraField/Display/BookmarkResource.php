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
 *   id = "bookmark_resource",
 *   label = @Translation("Bookmark resource"),
 *   bundles = {
 *     "node.resource",
 *     "node.resource_youtube",
 *   }
 * )
 */
class BookmarkResource extends ExtraFieldDisplayFormattedBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t('Bookmark resource');
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
    $moduleHandler = \Drupal::service('module_handler');
    if ($moduleHandler->moduleExists('soc_bookmarks')) {
      $downloadable = $entity->get('field_res_downloadable')->getString();
      if (!empty($downloadable) && $downloadable == 1) {
        $file = $entity->get('field_res_remote_file_url');
        if (!empty($file) && !empty($file->target_id)) {
          $id = $entity->id();
          $url = Url::fromRoute('soc_bookmarks.add_item', ['item_id' => $id])->toString();
          $link = "<a class='add-to-bookmarks ajax-soc-content-list' data-soc-content-list-ajax='1' 
          data-soc-content-list-item='$id' href='$url'></a>";
          return [
            ['#markup' => $link],
          ];
        }
      }
    }
    return [
      ['#markup' => ""],
    ];
  }

}
