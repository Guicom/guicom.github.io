<?php

namespace Drupal\soc_nextpage\Event;

/**
 * Class PendingProductEvents.
 *
 * @package Drupal\soc_nextpage\Event
 */
final class PendingProductEvents {
  /**
   * Name of the event fired after a new pending product is created.
   *
   * @Event
   *
   * @var string
   */
  const PENDING_PRODUCT_CREATED = 'pending.product.created';

  /**
   * Name of the event fired after a pending product is updated.
   *
   * @Event
   *
   * @var string
   */
  const PENDING_PRODUCT_UPDATED = 'pending.product.updated';

  /**
   * Name of the event fired after a pending product is turned to user.
   *
   * @Event
   *
   * @var string
   */
  const PENDING_PRODUCT_VALIDATED = 'pending.product.validated';
}
