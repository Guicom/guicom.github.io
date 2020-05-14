<?php

namespace Drupal\soc_blocks_promotion\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Block promotion entity type entity.
 *
 * @ConfigEntityType(
 *   id = "block_promotion_entity_type",
 *   label = @Translation("Block promotion entity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\soc_blocks_promotion\BlockPromotionEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\soc_blocks_promotion\Form\BlockPromotionEntityTypeForm",
 *       "edit" = "Drupal\soc_blocks_promotion\Form\BlockPromotionEntityTypeForm",
 *       "delete" = "Drupal\soc_blocks_promotion\Form\BlockPromotionEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\soc_blocks_promotion\BlockPromotionEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "block_promotion_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "block_promotion_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/block_promotion_entity_type/{block_promotion_entity_type}",
 *     "add-form" = "/admin/structure/block_promotion_entity_type/add",
 *     "edit-form" = "/admin/structure/block_promotion_entity_type/{block_promotion_entity_type}/edit",
 *     "delete-form" = "/admin/structure/block_promotion_entity_type/{block_promotion_entity_type}/delete",
 *     "collection" = "/admin/structure/block_promotion_entity_type"
 *   }
 * )
 */
class BlockPromotionEntityType extends ConfigEntityBundleBase implements BlockPromotionEntityTypeInterface {

  /**
   * The Block promotion entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Block promotion entity type label.
   *
   * @var string
   */
  protected $label;

}
