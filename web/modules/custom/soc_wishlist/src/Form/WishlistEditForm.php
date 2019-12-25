<?php

namespace Drupal\soc_wishlist\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Element\RenderElement;
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
    $items = $this->wishlistManager->loadSavedItems();

    // Set header.
    $header = [
      'picture' => t('Picture'),
      'reference' => t('Reference'),
      'description' => t('Description'),
      'quantity' => t('Quantity'),
    ];

    // Prepare tableselect field.
    $options = [];
    foreach ($items as $result) {
      if ($result['node'] instanceof Node) {
        $extId = $result['node']->get('field_reference_extid')->value;
        $mediaId = $result['node']->get('field_reference_picture')->target_id;
        $picture = $this->mediaApi->getFileUriFromMediaId($mediaId);
        $options[$extId] = [
          'picture' => [
            'data' => [
              '#theme' => 'image_style',
              '#style_name' => 'thumbnail',
              '#uri' => $picture,
            ],
          ],
          'reference' => $result['node']->get('field_reference_ref')->value,
          'description' => $result['node']->getTitle(),
          'quantity' => [
            'data' => [
              '#type' => 'number',
              '#title' => 'Quantity',
              '#title_display' => 'invisible',
              '#value' => $result['quantity'],
              '#name' => 'quantity[' . $extId . ']',
              '#attributes' => [
                'data-extid' => $extId,
              ],
              '#size' => 2,
              '#prefix' => '<span id="wishlist_quantity_' . $extId . '">',
              '#suffix' => '</span>',
              '#ajax' => [
                'callback' => [$this, 'updateItems'],
                'event' => 'change',
                'wrapper' => 'wishlist_quantity_' . $extId,
                'progress' => [
                  'type' => 'throbber',
                  'message' => $this->t('Updating quantity...'),
                ],
              ],
            ],
          ],
        ];
      }
    }

    // Form wrapper for AJAX
    $form['wrapper'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'wishlist_form_wrapper',
      ],
    ];

    // Create fields.
    $form['wrapper']['items'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
      '#empty' => t('No items found!'),
      '#prefix' => '<div>',
      '#suffix' => '</div>',
      '#process' => [
        '::processTable',
        [Tableselect::class, 'processTableselect']
      ]
    ];

    // Link
    $form['wrapper']['links'] = [
      '#type' => 'item',
      '#markup' => '<a href="/wishlist/export/csv">CSV</a> | <a href="/wishlist/export/xls">XLS</a> | <a href="/wishlist/export/xlsx">XLSX</a> | <a href="/wishlist/export/pdf">PDF</a>',
    ];

    $form['wrapper']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Remove selected'),
      '#ajax' => [
        'callback' => [static::class, 'updateItems'],
        'wrapper' => 'wishlist_form_wrapper',
      ],
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

  public function processTable(&$element, FormStateInterface $form_state, &$complete_form) {
    foreach (array_keys($element['#options']) as $option) {
      foreach (array_keys($element['#options'][$option]) as $col) {
        if (is_array($element['#options'][$option][$col]) && isset($element['#options'][$option][$col]['data'])) {
          $element['#options'][$option][$col]['data']['#name'] = implode('-', [$element['#name'], $option, $col]);
          $element['#options'][$option][$col]['data']['#id'] = implode('-', [$element['#id'], $option, $col]);
          $element['#options'][$option][$col]['data'] = RenderElement::preRenderAjaxForm($element['#options'][$option][$col]['data']);
        }
      }
    }
    return $element;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function updateItems(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

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
        $this->messenger->addStatus($this->t('Reference %extid has been deleted.', ['%extid' => $deletedItem]));
        if (array_key_exists($extId, $form['items'])) {
          unset($form['items'][$extId]);
        }
      }
    }

    return $response;
  }

}
