<?php

namespace Drupal\soc_addtoany\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides an 'custom floating AddToAny' block.
 *
 * @Block(
 *   id = "soc_floating_addtoany_block",
 *   admin_label = @Translation("SOC - Floating AddToAny buttons"),
 * )
 */
class SocCustomAddToAnyBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    if (is_numeric($node)) {
      $node = Node::load($node);
    }
    $data = addtoany_create_entity_data($node);
    return [
      '#addtoany_html'              => \Drupal::token()->replace($data['addtoany_html'], ['node' => $node]),
      '#link_url'                   => $data['link_url'],
      '#link_title'                 => $data['link_title'],
      '#button_setting'             => $data['button_setting'],
      '#button_image'               => $data['button_image'],
      '#universal_button_placement' => $data['universal_button_placement'],
      '#buttons_size'               => $data['buttons_size'],
      '#theme'                      => 'soc_floating_addtoany_block',
      '#cache'                      => [
        'contexts' => ['url'],
      ],
    ];
  }

}
