<?php

namespace Drupal\soc_nextpage\Service\Manager;

use Drupal;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\soc_nextpage\Service\NextpageApi;

/**
 * Class ProductManager
 *
 * @package Drupal\soc_nextpage\Service\Manager
 */
class ProductManager {
  
  /**
   * @param $pendingProduct
   *
   * @return \Drupal\Core\Entity\EntityInterface|mixed|string|void|null
   */
  public function handle($pendingProduct) {
    if ($pendingProduct->ElementType == 3) {
      if ($entity = $this->loadByExtID($pendingProduct->ExtID, 'paragraph')) {
        // Update reference.
        $entity = $this->updateReference($entity, $pendingProduct);
      }
      else {
        // Create reference.
        $entity = $this->createReference($pendingProduct);
      }
      $entity = $this->createReference($pendingProduct);
    }
    else {
      if ($entity = $this->loadByExtID($pendingProduct->ExtID, 'node')) {
        // Update product.
        $entity = $this->updateProduct($entity, $pendingProduct);
      }
      else {
        // Create product.
        $entity = $this->createProduct($pendingProduct);
      }
    }
    return $entity;
  }
  
  /**
   * @param $product
   *
   * @return \Drupal\Core\Entity\EntityInterface
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function createProduct($product) {
    $node = \Drupal::entityTypeManager()->getStorage('node')->create([
      'type'        => 'product',
    ]);
    
    $this->saveProduct($node, $product);
    return $node;
  }
  
  /**
   * @param $node
   * @param $product
   *
   * @return mixed
   */
  function updateProduct($node, $product) {
    $this->saveProduct($node, $product);
    return $node;
  }
  
  /**
   * @param $node
   * @param $product
   */
  function saveProduct(&$node, $product) {
    $node->set('title', $product->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value . ' - ' . $product->Values->DC_P_ASSORTMENT_WIDTH->Value);
    $node->set('field_main_picture_url', $product->Values->DC_P_PRODUCT_MAIN_PICTURE->Value);
    $node->set('field_main_picture_title', $product->Values->DC_P_PRODUCT_MAIN_PICTURE_TITLE->Value);
    $node->set('field_product_name', $product->Values->DC_P_PRODUCT_NAME->Value);
    $node->set('field_json_product_data', $this->formatJsonField($product->Values));
    $node->set('field_product_extid', $product->ExtID);
    $node->save();
  }
  
  /**
   *
   */
  public function delete() {
    // @todo : delete script.
  }
  
  /**
   * @param $reference
   *
   * @return \Drupal\Core\Entity\EntityInterface
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function createReference($reference) {
    $paragraph = \Drupal::entityTypeManager()->getStorage('paragraph')->create([
      'type'        => 'product_reference',
      'field_json_reference_data' => $this->formatJsonField($reference->Values),
      'field_reference_extid' => $reference->ExtID,
    ]);
    $this->saveReference($paragraph, $reference);
    return $paragraph;
  }
  
  /**
   * @param $paragraph
   * @param $reference
   */
  public function updatereference($paragraph, $reference) {
    $paragraph->set('field_json_reference_data', $this->formatJsonField($reference->Values));
    $paragraph->set('field_reference_extid', $reference->ExtID);
    
    $this->saveReference($reference, $paragraph);
    return $paragraph;
  }
  
  /**
   * @param $reference
   * @param $paragraph
   *
   * @return \Drupal\Core\Entity\EntityInterface|string|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function saveReference(&$reference, $paragraph) {
    if ($node = $this->loadByExtID($reference->ParentExtID, 'node')) {
      $current = $node->get('field_references')->getValue();
      $current[] = array(
        'target_id' => $paragraph->id(),
        'target_revision_id' => $paragraph->getRevisionId(),
      );
      $node->set('field_references', $current);
    }
    else {
      $node = \Drupal::entityTypeManager()->getStorage('node')->create([
        'type'        => 'product',
        'title'       => 'Temp-title - ' . $reference->ParentExtID,
        'field_product_extid' => $reference->ParentExtID,
        'field_references' => [
          'target_id' => $paragraph->id(),
          'target_revision_id' => $paragraph->getRevisionId(),
        ]
      ]);
    }
    $node->save();
  
    return $node;
  }
  
  /**
   * @param $extID
   * @param $type
   *
   * @return \Drupal\Core\Entity\EntityInterface|string|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function loadByExtID($extID, $type) {
    $this->getEntityInfo($type);
    $entity = '';
    $query = \Drupal::entityQuery($this->entityInfo["name"]);
    $query->condition('type', $this->entityInfo["type"]);
    $query->condition($this->entityInfo["field"], $extID);
    $result = $query->execute();
    if (!empty($result)) {
      $id = reset($result);
      $entity = \Drupal::entityTypeManager()->getStorage($this->entityInfo["name"])->load($id);
    }
    return $entity;
  }
  
  /**
   * @param $entityType
   */
  public function getEntityInfo($entityType) {
    $entityInfo = [];
    switch ($entityType) {
      case 'paragraph':
        $this->entityInfo['name'] = 'paragraph';
        $this->entityInfo['type'] = 'product_reference';
        $this->entityInfo['field'] = 'field_reference_extid';
      case 'node':
      default:
        $this->entityInfo['name'] = 'node';
        $this->entityInfo['type'] = 'product';
        $this->entityInfo['field'] = 'field_product_extid';
    }
  }
  
  /**
   * @param $values
   *
   * @return false|string
   */
  public function formatJsonField($values) {
    $dico = Drupal::service('soc_nextpage.nextpage_api');
    $d = $dico->characteristicsDictionary();
    $json = [];
    foreach ($values as $key => $value) {
      if (isset($d[$key])) {
        $dico_carac = $d[$key];
        if ($dico_carac != NULL) {
          if (!isset($json[$dico_carac->LibelleDossier])) {
            $json[$dico_carac->LibelleDossier] = [
              'group_name' => $dico_carac->LibelleDossier,
            ];
          }
          $value_data = [];
          
          switch ($dico_carac->TypeCode) {
            case 'CHOIX':
            case 'LISTE':
              foreach ($dico_carac->Values as $choices) {
                foreach ($value->Values as $val) {
                  if ($val->ValueID == $choices->ValeurID) {
                    $value_data[] = $choices->Valeur;
                  }
                }
              }
              $value = [
                'id' => $dico_carac->ExtID,
                'type' => $dico_carac->TypeCode,
                'value' => $value_data,
              ];
              break;
            default:
              $value = [
                'id' => $dico_carac->ExtID,
                'type' => $dico_carac->TypeCode,
                'value' => $value->Value ? $value->Value : '',
              ];
              break;
          }
          $json[$dico_carac->LibelleDossier]['value'][$value["id"]] = $value;
        }
      }
    }
    return json_encode($json);
  }
}
