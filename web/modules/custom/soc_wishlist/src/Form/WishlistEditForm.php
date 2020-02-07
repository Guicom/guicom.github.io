<?php

namespace Drupal\soc_wishlist\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Render\Element\StatusMessages;
use Drupal\Core\Render\Element\Tableselect;
use Drupal\node\Entity\Node;
use Drupal\soc_core\Service\MediaApi;
use Drupal\soc_wishlist\Service\Manager\WishlistManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
    $items = $this->wishlistManager->loadSavedItems();

    // Form wrapper for AJAX
    $form['wrapper_wishlist'] = [
      '#type' => 'container',
    ];
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
            'callback' => [$this, 'updateItems'],
            'event' => 'change',
            'wrapper' => 'wishlist_quantity_wrapper_'.$extId,
          ],
        ];

        $form['wrapper_wishlist'][$i]['wishlist_action_'.$extId] = [
          '#title' => 'action',
          '#title_display' => 'invisible',
          '#type' => 'checkboxes',
          '#options' =>  ['checked' => 'checked'],
          '#default_value' => ['checked'],
          '#prefix' => '<div id="wishlist_wishlist_action_wrapper_'.$extId.'">',
          '#suffix' => '</div>',
          /*'#ajax' => [
            'callback' => [$this, 'updateSelect'],
            'event' => 'change',
            'progress' => ['type' => 'none'],
          ],*/
        ];
      }
      $i++;
    }

    $form['wrapper_wishlist']['nb'] = [
      '#value' => $i
    ];

    $confirmRemoveClass = 'confirm-remove';
    for($i = 0; $i < 2; ++$i) {
      // Export btn
      $form['actions']['exports'][$i]['xls'][$i] = [
        '#type' => 'submit',
        '#value' => t('Export to an XLS file'),
      ];

      // Export btn
      $form['actions']['exports'][$i]['pdf'][$i] = [
        '#type' => 'submit',
        '#value' => t('Export to an PDF file'),
      ];

      // Export btn
      $form['actions']['exports'][$i]['csv'][$i] = [
        '#type' => 'submit',
        '#value' => t('Export to an CSV file'),
      ];

      // Update quantity
      if (sizeof($items)) {
        $form['actions']['others'][$i]['update'][$i] = [
          '#type' => 'submit',
          '#value' => t('Remove selected'),
          '#ajax' => [
            'callback' => [static::class, 'updateItems'],
            'wrapper' => 'wishlist_form_wrapper',
            'progress' => ['type' => 'none'],
          ],
        ];
        $form['actions']['others'][$i]['update'][$i]['#attributes']['class'][] = $confirmRemoveClass;
      }
    }

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
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function updateSelect(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $userInput = $form_state->getUserInput();
    $extids = [];
    foreach ($form['wrapper_wishlist'] as $key => $item ) {
      if (is_numeric($key)) {
        if (!empty($item['extid']['#value'])) {
          $extids[] = $item['extid']['#value'];
        }
      }
    }
    return $response;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function updateItems(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $deletedItems = [];

    /** @var \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager */
    $wishlistManager = \Drupal::service('soc_wishlist.wishlist_manager');
    $userInput = $form_state->getUserInput();
    if (isset($userInput['items']) && sizeof($userInput['items'])) {
      $wishlistManager->loadSavedItems();
      $items = $userInput['items'];
      foreach ($items as $extId => $selected) {
        if (array_key_exists('items-' . $extId . '-quantity', $userInput)) {
          $quantity = $userInput['items-' . $extId . '-quantity'];
          if (is_numeric($quantity)) {
            if ($quantity > 0) {
              $wishlistManager->setQuantity($extId, $quantity);
            }
            else {
              $wishlistManager->remove($extId);
              $response->addCommand(new InvokeCommand('#item_line_'.$extId, 'hide'));
              $deletedItems[] = $extId;
            }
          }
        }
        if ($extId === $selected) {
          $wishlistManager->remove($extId);
          $deletedItems[] = $extId;
        }
      }
      try {
        $wishlistManager->updateCookie();
      } catch (\Exception $e) {
        $this->messenger->addError($e->getMessage());
      }
    }

    if (sizeof($deletedItems)) {
      foreach ($deletedItems as $deletedItem) {
        $response->addCommand(new InvokeCommand('#item_line_'.$deletedItem, 'hide'));
      }
      $count = sizeof($deletedItems);
      $messageSingular = "@count item deleted.";
      $messagePlural = "@count items deleted.";
      $message = \Drupal::translation()->formatPlural($count, $messageSingular, $messagePlural);
      \Drupal::service('messenger')->addStatus($message);
      $messages = \Drupal::service('renderer')->renderRoot(StatusMessages::renderMessages());
      $response->addCommand(new PrependCommand('#wishlist_form_wrapper', $messages));
    }

    return $response;
  }

  public static function getTitle() {
    $language = \Drupal::languageManager()->getCurrentLanguage();
    return \Drupal::config('soc_wishlist.settings')->get('page_title_' . $language->getId())
      ?? t('My wishlist');
  }

}
