<?php

use Drupal\Component\Utility\UrlHelper;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\node\Entity\Node;

class EventContext extends RawDrupalContext {

  /**
   * Set event date.
   *
   * @Given I set the event date
   */
  public function setDate() {

    // get ID of event
    $path = UrlHelper::parse($this->getSession()->getCurrentUrl());
    $arrayUrl = explode('/', $path['path']);
    array_pop($arrayUrl); // remove the "edit" at the end
    $nid = end($arrayUrl);

    $node = Node::load($nid);
    try {
      $now = new DateTime('2020-03-01');
      $now->setTimezone(new \DateTimezone(DateTimeItemInterface::STORAGE_TIMEZONE));
      $end = new DateTime('2025-01-01');
      $end->setTimezone(new \DateTimezone(DateTimeItemInterface::STORAGE_TIMEZONE));
      $node->set('field_event_date', [
        'value' => $now->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT),
        'end_value' => $end->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT),
      ]);
      try {
        $node->save();
      } catch (\Exception $e) {
      }
    } catch (Exception $e) {
    }
  }

}
