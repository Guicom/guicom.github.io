<?php
/**
 * Get thank you page associate to current landing page.
 */

namespace Drupal\soc_premium_content\Service;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

class SocPremiumContentNode {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  protected $resource_vocabulary_type;

  /**
   * SocPremiumContentNode constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, $resource_vocabulary_type) {
    $this->entityTypeManager = $entityTypeManager;
    $this->resource_vocabulary_type = $resource_vocabulary_type;
  }

  /**
   * Get all Thank You Page with reference to Landing Page
   *
   * @param Node $node
   *
   * @return array|int|null
   */
  public function getAllThankYouPageFromLandingPage($node) {
    if (strcmp($node->getType(), 'landing_page') === 0) {
      return \Drupal::entityQuery('node')
        ->condition('type', 'thank_you_page')
        ->condition('field_landing_page', $node->id())
        ->execute();
    }
    else {
      return NULL;
    }
  }

  /**
   * Auto-create Resource content when creating or updating a landing page
   * @param \Drupal\node\Entity\Node $node
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function autoCreateResourceFromPremiumContent(EntityInterface $node) {
    $title = $node->getTitle();
    /**
     * @var \Drupal\taxonomy\Entity\Term[] $premiumContentTypeTermes
     */
    $premiumContentTypeTermes = $node->get('field_premium_content_type')
      ->referencedEntities();
    $resourceTypes = [];
    if (!empty($premiumContentTypeTermes)) {

      foreach ($premiumContentTypeTermes as $premiumContentTypeTerme) {
        $termResourceType = $this->entityTypeManager->getStorage('taxonomy_term')
          ->loadByProperties([
            'vid' => $this->resource_vocabulary_type,
            'name' => $premiumContentTypeTerme->label(),
          ]);
        if (!empty($termResourceType)) {
          $resourceTypes[] = reset($termResourceType);
        }
        else {
          $termResourceType = $this->entityTypeManager->getStorage('taxonomy_term')
            ->create([
              'vid' => $this->resource_vocabulary_type,
              'name' => $premiumContentTypeTerme->label(),
            ]);
          $termResourceType->save();
          $resourceTypes[] = $termResourceType;
        }
      }
    }
    pathauto_entity_update($node);
    $url = Url::fromRoute('entity.node.canonical', ['node' => $node->id()], ['absolute' => TRUE]);
    $url = $url->toString();
    /**
     * @var Node $nodeResource
     */
    $moderation = $node->get('moderation_state')->getValue();
    if ($nodeResource = $node->get('field_resource')->referencedEntities()) {
      $nodeResource = reset($nodeResource);
      $nodeResource->set('status', $node->isPublished())
        ->setTitle($title)
        ->set('langcode', $node->language()->getId())
        ->set('field_res_resource_type', $resourceTypes)
        ->set('field_res_link_url', $url)
        ->set('field_res_title', $title)
        ->set('field_res_original_title', $title)
        ->set('moderation_state', $moderation[0]['value'])
        ->save();
    }
    else {
      $nodeResource = $this->entityTypeManager->getStorage('node')->create([
        'status' => $node->isPublished(),
        'type' => 'resource',
        'title' => $title,
        'field_res_resource_type' => $resourceTypes,
        'field_res_link_url' => $url,
        'langcode' => $node->language()->getId(),
        'field_res_title' => $title,
        'field_res_original_title' => $title,
      ]);
      $nodeResource->set('moderation_state', $moderation[0]['value']);
      $nodeResource->save();
      $node->set('field_resource', $nodeResource->id());
      $node->save();
    }
  }
}
