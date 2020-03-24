<?php

namespace Drupal\soc_content_list\Model;


class ContentList {

  /** @var $items */
  protected $items;

  /**
   * @return mixed
   */
  public function getItems():array {
    return $this->items ?? [];
  }

  /**
   * @param mixed $items
   */
  public function setItems($items): void {
    $this->items = $items;
  }

}
