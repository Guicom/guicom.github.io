<?php

namespace Drupal\soc_wishlist\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Element\StatusMessages;
use Drupal\node\Entity\Node;
use Drupal\soc_core\Service\MediaApi;
use Drupal\soc_wishlist\Service\Manager\WishlistManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;


class WishlistEditForm extends FormBase {

  /** @var \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager */
  private $wishlistManager;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The Media API.
   *
   * @var \Drupal\soc_core\Service\MediaApi
   */
  protected $mediaApi;

  /**
   * WishlistController constructor.
   *
   * @param \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\soc_core\Service\MediaApi $mediaApi
   */
  public function __construct(WishlistManager $wishlistManager,
                              MessengerInterface $messenger,
                              MediaApi $mediaApi) {
    $this->wishlistManager = $wishlistManager;
    $this->messenger = $messenger;
    $this->mediaApi = $mediaApi;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_wishlist.wishlist_manager'),
      $container->get('messenger'),
      $container->get('soc_core.media_api')
    );
  }

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'soc_wishlist_wishlist_edit_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#theme'] = 'soc_wishlist_custom_form';
    if ( empty($this->wishlistManager) ) {
      $this->wishlistManager = \Drupal::service('soc_wishlist.wishlist_manager');
    }
    $items = $this->wishlistManager->loadSavedItems();


    // Form wrapper for AJAX
    $form['wrapper_wishlist'] = [
      '#type' => 'container',
    ];

    $form['select_all'] = [
      '#title' => 'Select all',
      '#title_display' => 'invisible',
      '#type' => 'checkboxes',
      '#options' =>  ['select' => ''],
      '#ajax' => [
        'callback' => [$this, 'updateSelect'],
        'event' => 'change',
        'progress' => ['type' => 'none'],
      ],
    ];

    $exportList = (isset($_SESSION['socomec_wishlist_export'])) ? $_SESSION['socomec_wishlist_export'] : '';
    $i = 0;
    foreach ($items as $result) {
      if ($result['node'] instanceof Node) {
        $extId = $result['node']->get('field_reference_extid')->value;
        $mediaId = $result['node']->get('field_reference_picture')->target_id;
        $picture = '';
        if (!empty($mediaId)) {
          $picture = $this->mediaApi->getFileUriFromMediaId($mediaId);
        }

        $form['wrapper_wishlist'][$i]['extid'] = [
          '#value' => $extId
        ];

        $form['wrapper_wishlist'][$i]['picture_'.$extId] = [
            '#theme' => 'image_style',
            '#style_name' => 'thumbnail',
            '#uri' => $picture,
        ];

        $form['wrapper_wishlist'][$i]['model_'.$extId] = [
          '#markup' => $result['node']->get('field_reference_ref')->value,
        ];

        $form['wrapper_wishlist'][$i]['reference_'.$extId] = [
          '#markup' => $result['node']->get('field_reference_ref')->value,
        ];

        $form['wrapper_wishlist'][$i]['description_'.$extId] = [
          '#markup' => $result['node']->getTitle(),
        ];

        $form['wrapper_wishlist'][$i]['quantity_'.$extId] = [
          '#type' => 'number',
          '#title' => 'Quantity',
          '#title_display' => 'invisible',
          '#default_value' => $result['quantity'],
          '#prefix' => '<div id="wishlist_quantity_wrapper_'.$extId.'">',
          '#suffix' => '</div>',
          '#size' => 2,
          '#ajax' => [
            'callback' => [$this, 'updateQuantity'],
            'event' => 'change',
            'progress' => ['type' => 'none'],
            'wrapper' => 'wishlist_quantity_wrapper_'.$extId,
          ],
        ];

        $default_value = [];
        if (!empty($exportList[$extId])) {
          $default_value = [$extId];
        }
        $form['wrapper_wishlist'][$i]['wishlist_action_'.$extId] = [
          '#title' => 'action',
          '#title_display' => 'invisible',
          '#type' => 'checkboxes',
          '#options' =>  [$extId => ''],
          '#default_value' => $default_value,
          '#ajax' => [
            'callback' => [$this, 'updateSession'],
            'event' => 'change',
            'progress' => ['type' => 'none'],
          ],
          '#attributes' => ['class' => ['wishlist-action-item']]
        ];
        $i++;
      }
    }

    $form['wrapper_wishlist']['nb'] = [
      '#value' => $i
    ];

    $confirmRemoveClass = 'confirm-remove';
    for($i = 0; $i < 2; ++$i) {
      // Export btn
      $form['actions']['exports'][$i]['xls'] = [
        '#type' => 'link',
        '#title' => t('Export to an XLS file'),
        '#url' => Url::fromRoute('soc_wishlist.export', ['type' => 'xls']),
        "#prefix" => '<div class="dropdown-item-text">',
        "#suffix" => '</div>'
      ];

      $form['actions']['exports'][$i]['pdf'] = [
        '#type' => 'link',
        '#title' => t('Export to an PDF file'),
        '#url' => Url::fromRoute('soc_wishlist.export', ['type' => 'pdf']),
        "#prefix" => '<div class="dropdown-item-text">',
        "#suffix" => '</div>'
      ];

      $form['actions']['exports'][$i]['csv'] = [
        '#type' => 'link',
        '#title' => t('Export to an CSV file'),
        '#url' => Url::fromRoute('soc_wishlist.export', ['type' => 'csv']),
        "#prefix" => '<div class="dropdown-item-text">',
        "#suffix" => '</div>'
      ];

      // Update quantity
      if (sizeof($items)) {
        $form['actions']['others'][$i]['remove'][$i] = [
          '#type' => 'submit',
          '#value' => t('Remove selected'),
          '#ajax' => [
            'callback' => [static::class, 'removeItems'],
            'wrapper' => 'wishlist_form_wrapper',
            'progress' => ['type' => 'none'],
          ],
        ];
        $form['actions']['others'][$i]['remove'][$i]['#attributes']['class'][] = $confirmRemoveClass;
      }
    }

    $form['#attached']['library'][] = 'soc_wishlist/wishlist-actions';

    // Confirm before deleting
    $form['#attached']['library'][] = 'core/drupal.ajax';
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['#attached']['library'][] = 'soc_wishlist/ajax-confirm';
    $form['#attached']['drupalSettings']['ajaxConfirm'][$confirmRemoveClass] = [
      'title' => $this->t('Confirm element removal'),
      'text' => $this->t('Are you sure that you want to remove this element?'),
      'buttons' => [
        'button_confirm' => ['text' => $this->t('Yes')],
        'button_reject' => ['text' => $this->t('No')],
      ]
    ];

    $form['#attached']['library'][] = 'soc_wishlist/wishlist-datatable';
    $form['#attached']['drupalSettings']['wishlistDatatable'] = [
      'searchPlaceholder' => $this->t('Search, filter...'),
    ];
    // Return form.

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * @param $input
   *
   * @return array
   */
  public static function customSelectedItems($input) {
    $selectItems = [];
    if (!empty($input) && is_array($input)) {
      foreach ($input as $keyItem => $item) {
        if( substr( $keyItem, 0, 16 ) === "wishlist_action_") {
          $keys = array_keys($item);
          if (!empty($item[$keys[0]])) {
            $selectItems[$item[$keys[0]]] = $item[$keys[0]];
          }
        }
      }
    }
    return $selectItems;
  }


  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function updateSelect(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $input = $form_state->getUserInput();
    $items = self::customSelectedItems($input);
    if (sizeof($items)) {
      $_SESSION['socomec_wishlist_export'] = $items;
    }

    return $response;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function updateSession(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $input = $form_state->getUserInput();
    $items = self::customSelectedItems($input);
    if (sizeof($items)) {
      $_SESSION['socomec_wishlist_export'] = $items;
    }

    return $response;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function updateQuantity(array &$form, FormStateInterface $form_state) {
    $input = $form_state->getUserInput();
    $selectItems = [];
    if (!empty($input) && is_array($input)) {
      foreach ($input as $keyItem => $item) {
        if ($keyItem === $input['_triggering_element_name']) {
          $explode = explode('quantity_', $keyItem);
          $selectItems[$explode[1]] = $item;
        }
      }
    }

    if (!empty($selectItems)) {
      $wishlistManager = \Drupal::service('soc_wishlist.wishlist_manager');
      $wishlistManager->loadSavedItems();
      foreach ($selectItems as $extId => $quantity) {
        $wishlistManager->setQuantity($extId, $quantity);
      }
      try {
        $wishlistManager->updateCookie();
      } catch (\Exception $e) {
        //$this->messenger->addError($e->getMessage());
      }
    }
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function removeItems(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $input = $form_state->getUserInput();
    $items = self::customSelectedItems($input);
    if (sizeof($items)) {
      /** @var \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager */
      $wishlistManager = \Drupal::service('soc_wishlist.wishlist_manager');
      $wishlistManager->loadSavedItems();
      foreach ($items as $deletedItem) {
        $removedLine = '#item_line_'.$deletedItem;
        $wishlistManager->remove($deletedItem);
        try {
          $wishlistManager->updateCookie();
          $response->addCommand(new RemoveCommand($removedLine));
        } catch (\Exception $e) {
          //$this->messenger->addError($e->getMessage());
        }
      }
      $count = sizeof($items);
      $messageSingular = "@count item deleted.";
      $messagePlural = "@count items deleted.";
      $message = \Drupal::translation()->formatPlural($count, $messageSingular, $messagePlural);
      \Drupal::service('messenger')->addStatus($message);
      $messages = \Drupal::service('renderer')->renderRoot(StatusMessages::renderMessages());
      $response->addCommand(new PrependCommand('#wishlist_form_message', $messages));
    }

    return $response;
  }

  public static function getTitle() {
    $language = \Drupal::languageManager()->getCurrentLanguage();
    return \Drupal::config('soc_wishlist.settings')->get('page_title_' . $language->getId())
      ?? t('My wishlist');
  }

}
