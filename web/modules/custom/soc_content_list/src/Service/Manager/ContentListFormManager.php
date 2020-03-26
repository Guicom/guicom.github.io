<?php

namespace Drupal\soc_content_list\Service\Manager;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Url;
use Drupal\Core\Link;

class ContentListFormManager {

  /**
   * ContentListFormManager constructor.
   *
   * @param \Drupal\soc_content_list\Model\ContentList $content_list
   * @param string $cookie_name
   * @param \Drupal\Core\Config\Config $settings
   * @param string $bundle
   * @param string $referenced_field
   */
  public function __construct() {
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param $name
   * @param $session
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function updateSession(array &$form, FormStateInterface $form_state, string $name, string $session) {
    $response = new AjaxResponse();

    $input = $form_state->getUserInput();
    $items = self::customSelectedItems($input, $name);
    $_SESSION[$session] = [];
    if (sizeof($items)) {
      $_SESSION[$session] = $items;
    }

    return $response;
  }

  public function attached(array &$form, string $confirmRemoveClass) {
    $form['#attached']['library'][] = 'soc_content_list/list-actions';

    // Confirm before deleting
    $form['#attached']['library'][] = 'core/drupal.ajax';
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['#attached']['library'][] = 'soc_content_list/ajax-confirm';
    $form['#attached']['drupalSettings']['ajaxConfirm'][$confirmRemoveClass] = [
      'title' => t('Confirm element removal'),
      'text' => t('Are you sure that you want to remove this element?'),
      'buttons' => [
        'button_confirm' => ['text' => t('Yes')],
        'button_reject' => ['text' => t('No')],
      ]
    ];

    $form['#attached']['library'][] = 'soc_content_list/list-datatable';
    $form['#attached']['drupalSettings']['socListDatatable'] = [
      'searchPlaceholder' => t('Search, filter...'),
    ];
  }


  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function removeItems(array &$form, FormStateInterface $form_state, string $name, string $session, string $selector, Url $urlRedirect, ContentListManager $contentListManager) {
    $response = new AjaxResponse();
    $input = $form_state->getUserInput();
    $items = self::customSelectedItems($input, $name);
    if (sizeof($items)) {
      $contentListManager->loadSavedItems();
      $_SESSION[$session] = [];
      foreach ($items as $deletedItem) {
        $removedLine = '#item_line_'.$deletedItem;
        $contentListManager->remove($deletedItem);
        try {
          $contentListManager->updateCookie();
          $response->addCommand(new RemoveCommand($removedLine));
          $_SESSION[$session][$deletedItem] = $deletedItem;
        } catch (\Exception $e) {
          \Drupal::messenger()->addError($e->getMessage());
        }
      }
      $count = sizeof($items);

      $link = Link::fromTextAndUrl(t('Cancel deletion(s)'), $urlRedirect)->toString();
      \Drupal::messenger()->addMessage(t("@count item(s) deleted. @link", ["@count" => $count , "@link" => $link ]), 'status', TRUE);

      $message = [
        '#theme' => 'status_messages',
        '#message_list' => drupal_get_messages(),
      ];

      $messages = \Drupal::service('renderer')->render($message);
      $response->addCommand(new PrependCommand($selector, $messages));
    }

    return $response;
  }

  /**
   * @param $input
   * @param $name
   *
   * @return array
   */
  public static function customSelectedItems(array $input, string $name) {
    $selectItems = [];
    if (!empty($input) && is_array($input)) {
      foreach ($input as $keyItem => $item) {
        if (substr($keyItem, 0, 16) === $name) {
          $keys = array_keys($item);
          if (!empty($item[$keys[0]])) {
            $selectItems[$item[$keys[0]]] = $item[$keys[0]];
          }
        }
      }
    }
    return $selectItems;
  }


}
