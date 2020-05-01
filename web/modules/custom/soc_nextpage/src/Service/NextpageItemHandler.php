<?php

namespace Drupal\soc_nextpage\Service;

use Drupal;
use Drupal\Core\Database\Connection;


class NextpageItemHandler  {

  /**
   * @var \Drupal\soc_nextpage\Service\NextpageApi
   */
  private $nextpageApi;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  private $connection;

  /** @var array $entityInfo */
  protected $entityInfo;

  function __construct(NextpageApi $nextpageApi, Connection $connection) {
    $this->nextpageApi = $nextpageApi;
    $this->connection = $connection;
    $this->entityInfo = [];
  }

  /**
   * @param $extID
   * @param $type
   *
   * @return \Drupal\Core\Entity\EntityInterface|string|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function loadByExtID($extID, $entity_type, $type) {
    $this->getEntityInfo($entity_type);
    $entity = '';
    $query = \Drupal::entityQuery($this->entityInfo["name"]);
    $query->condition($this->entityInfo["type"], $type);
    $query->condition($this->entityInfo["field"], $extID);
    $result = $query->execute();
    if (!empty($result)) {
      $id = reset($result);
      $entity = \Drupal::entityTypeManager()->getStorage($this->entityInfo["name"])->load($id);
    }
    return $entity;
  }

  /**
   * @param $values
   *
   * @return false|string
   */
  public function formatJsonField($values) {
    /** @var \Drupal\soc_nextpage\Service\NextpageApi $nextpageApi */
    $nextpageApi = Drupal::service('soc_nextpage.nextpage_api');
    $dictionary = $nextpageApi->characteristicsDictionary('2');
    $json = [];
    foreach ($values as $key => $value) {
      if (isset($dictionary[$key])) {
        $dico_carac = $dictionary[$key];
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

  /**
   * @param $entityType
   */
  public function getEntityInfo($entityType) {
    switch ($entityType) {
      case 'paragraph':
        $this->entityInfo['name'] = 'taxonomy_term';
        $this->entityInfo['type'] = 'vid';
        $this->entityInfo['field'] = 'field_family_extid';
        break;
      case 'node':
      default:
        $this->entityInfo['name'] = 'node';
        $this->entityInfo['type'] = 'type';
        $this->entityInfo['field'] = 'field_reference_extid';
        break;
    }
  }

  public function getJsonField($field) {
    $dico = $this->nextpageApi->characteristicsDictionary('2');
    $dico_carac = $dico[$field->DicoCaracExtID];
    switch ($dico_carac->TypeCode) {
      case 'CHOIX':
      case 'LISTE':
        foreach ($dico_carac->Values as $choices) {
          foreach ($field->Values as $val) {
            if ($val->ValueID == $choices->ValeurID) {
              $value_data[] = $choices->Valeur;
            }
          }
        }
        $value = [
          'id' => $dico_carac->ExtID,
          'type' => $dico_carac->TypeCode,
          'value' => $value_data,
          'label' => $dico_carac->Name,
        ];
        break;
      default:
        $value = [
          'id' => $dico_carac->ExtID,
          'type' => $dico_carac->TypeCode,
          'value' => $field->Value ?? '',
          'label' => $dico_carac->Name,
        ];
        break;
    }
    return $value;
  }

  public function insertRelation($familyId, $productId, $familyParentId) {
    $result = $this->connection->insert('soc_nextpage_relations')
      ->fields([
        'family_id' => $familyId,
        'product_id' => $productId,
        'family_parent_id' => $familyParentId,
      ])
      ->execute();
  }

  public function getRelation($productId) {
    $sth = $this->connection->select('soc_nextpage_relations', 'snr')
      ->fields('snr', array('family_parent_id'))
      ->condition('snr.product_id', $productId);

    // Execute the statement
    $data = $sth->execute();
    $result = $data->fetchAll(\PDO::FETCH_OBJ);
    if (isset($result[0])) {
      $this->deleteRelation($productId);
      return $result;
    }
    return $result;
  }

  public function deleteRelation($productId) {
    $this->connection->delete('soc_nextpage_relations')
      ->condition('product_id', $productId)
      ->execute();
  }

  public function getFieldFromJson($json_value, $extid) {
    $data = NULL;
    if (isset($json_value->{$extid})) {
      $data = $json_value->{$extid}->value;
    }
    else {
      foreach ($json_value as $values) {
        if (isset($values->value)) {
          if (isset($values->value->{$extid})) {
            $data = $values->value->{$extid}->value;
          }
        }
      }
    }

    return $data;
  }

}
