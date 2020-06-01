<?php

namespace Drupal\soc_blocks_promotion\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BlockPromotionEntityTypeForm.
 */
class BlockPromotionEntityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $block_promotion_entity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $block_promotion_entity_type->label(),
      '#description' => $this->t("Label for the Block promotion entity type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $block_promotion_entity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityType::load',
      ],
      '#disabled' => !$block_promotion_entity_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $block_promotion_entity_type = $this->entity;
    $status = $block_promotion_entity_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Block promotion entity type.', [
          '%label' => $block_promotion_entity_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Block promotion entity type.', [
          '%label' => $block_promotion_entity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($block_promotion_entity_type->toUrl('collection'));
  }

}
