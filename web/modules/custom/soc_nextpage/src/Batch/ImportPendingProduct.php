<?php

namespace Drupal\soc_nextpage\Batch;


use Drupal\soc_nextpage\Service\Manager\ProductManager;

class ImportPendingProduct {

  /**
   * Add a pending user to the batch.
   *
   * @param $item
   * @param $context
   */
  public static function addPendingProduct($item, &$context) {
    switch ($item->ElementType) {
      // Familly case.
      case 1:
        // @TODO : Implement familly manager
        break;
      // Product case.
      case 2:
      case 3:
        $product = new ProductManager();
        $stat = $product->handle($item);
        break;
      default:
        break;
    }
//    // Get pending user.
//    $pendingUser = new PendingUser($item, FALSE, $item['pwdState'] ?? 1);
//    unset($item['pswState']);
//
//    // Get action display.
//    $actionDisplay = t('(create/update)');
//    if (PendingUser::STATUS_TO_REMOVE == $pendingUser->status) {
//      $actionDisplay = t('(remove)');
//    } elseif (PendingUser::STATUS_TO_UPDATE != $pendingUser->status) {
//      $actionDisplay = t('(unknown action)');
//    }

    // Proceed operation.
    $context['sandbox']['current_item'] = $item;
    // $context['message'] = 'Managing ' . $pendingUser->field_pu_identifier . ' ' . $actionDisplay;

//    $manager = \Drupal::service('prae_pending_users.pending_users_manager');
//    $result = $manager->handle($pendingUser);
//    $manager->formatResult($pendingUser, $result, $context);
  }

  /**
   * Pending users import batch callback.
   *
   * @param $success
   * @param $results
   * @param $operations
   */
  public static function addPendingProductCallback($success, $results, $operations) {
    if ($success) {
      $countTotal = $results['count'];
      $countError = count($results['errored'] ?? []);
      $countProcessed = $countTotal - $countError;
      $message = \Drupal::translation()->formatPlural(
        $countProcessed,
        '1/' . $countProcessed . ' pending user processed.', '@count/' . $countProcessed . ' pending users processed'
      );

      if (0 < $countTotal) {
        $message .= t(' : (' .
          count($results['created'] ?? []) . ' created/updated, ' .
          count($results['removed'] ?? []) . ' removed, ' .
          count($results['errored'] ?? []) . ' errored).');
      }

      // Adjust message type according to errors count.
      if (0 === $countError) {
        drupal_set_message($message);
      } else {
        drupal_set_message($message, 'warning');
      }

      // List errors.
      foreach ($results['errored'] ?? [] as $error) {
        drupal_set_message($error, 'error');
      }
    } else {
      $message = t('Finished with an error, please check your CSV file content...');
      drupal_set_message($message, 'error');
    }
  }
}
