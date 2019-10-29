<?php

namespace Drupal\soc_nextpage\Service\Manager;

use Drupal;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\soc_nextpage\Service\NextpageApi;

class ProductManager {
  
  public function handle($pendingProduct) {
    if ($pendingProduct->ElementType == 3) {
      $node = $this->createReference($pendingProduct);
    }
    else {
      if ($node = $this->loadByExtID($pendingProduct->ExtID)) {
        // Update product.
        $node = $this->update($node, $pendingProduct);
      }
      else {
        // Create product.
        $node = $this->create($pendingProduct);
      }
    }
    
    return $node;
  }
  
  public function create($product) {
    $node = \Drupal::entityTypeManager()->getStorage('node')->create([
      'type'        => 'product',
      'title'       => $product->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value . ' - ' . $product->Values->DC_P_ASSORTMENT_WIDTH->Value,
      'field_main_picture_url' => $product->Values->DC_P_PRODUCT_MAIN_PICTURE->Value,
      'field_main_picture_title' => $product->Values->DC_P_PRODUCT_MAIN_PICTURE_TITLE->Value,
      'field_product_name' => $product->Values->DC_P_PRODUCT_NAME->Value,
      'field_json_product_data' => $this->formatJsonField($product->Values),
      'field_product_extid' => $product->ExtID,
    ]);
    
    $this->save($node);
    return $node;
  }
  
  function update($node, $product) {
    $node->set('title', $product->Values->DC_P_PRODUCT_SHORT_DESCRIPTION->Value . ' - ' . $product->Values->DC_P_ASSORTMENT_WIDTH->Value);
    $node->set('field_main_picture_url', $product->Values->DC_P_PRODUCT_MAIN_PICTURE->Value);
    $node->set('field_main_picture_title', $product->Values->DC_P_PRODUCT_MAIN_PICTURE_TITLE->Value);
    $node->set('field_product_name', $product->Values->DC_P_PRODUCT_NAME->Value);
    $node->set('field_json_product_data', $this->formatJsonField($product->Values));
    $node->set('field_product_extid', $product->ExtID);
    
    $this->save($node);
    return $node;
  }
  
  function save(&$node) {
    $node->save();
  }
  
  public function delete() {
    // @todo : delete script.
  }
  
  public function createReference($reference) {
    $paragraph = \Drupal::entityTypeManager()->getStorage('paragraph')->create([
      'type'        => 'product_reference',
      'field_json_reference_data' => $this->formatJsonField($reference->Values),
      'field_reference_extid' => $reference->ExtID,
    ]);
    $paragraph->save();
    if ($node = $this->loadByExtID($reference->ParentExtID)) {
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
  
  public function loadByExtID($extID) {
    $node = '';
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'product');
    $query->condition('field_product_extid', $extID);
    $result = $query->execute();
    if (!empty($result)) {
      $nid = reset($result);
      $node = Node::load($nid);
    }
    return $node;
  }
  
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
