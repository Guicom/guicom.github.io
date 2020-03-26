<?php

namespace Drupal\soc_wishlist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_content_list\Service\Manager\ContentListFormManager;
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
   * @param \Drupal\soc_bookmarks\Service\Manager\BookmarkManager $bookmarkManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\soc_core\Service\MediaApi $mediaApi
   * @param \Drupal\soc_content_list\Service\Manager\ContentListFormManager $contentListFormManager
   */
  public function __construct(WishlistManager $wishlistManager,
                              MessengerInterface $messenger,
                              MediaApi $mediaApi,
                              ContentListFormManager $contentListFormManager) {
    $this->wishlistManager = $wishlistManager;
    $this->messenger = $messenger;
    $this->mediaApi = $mediaApi;
    $this->contentListFormManager = $contentListFormManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_wishlist.wishlist_manager'),
      $container->get('messenger'),
      $container->get('soc_core.media_api'),
      $container->get('soc_content_list.content_list_form_manager')
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

    // Form wrapper_wishlist for AJAX
    $form['wrapper_wishlist'] = ['#type' => 'container',];

    $form['select_all'] = [
      '#title' => 'Select all',
      '#title_display' => 'invisible',
      '#type' => 'checkboxes',
      '#options' =>  ['select' => ''],
      '#ajax' => [
        'callback' => [$this, 'updateSession'],
        'event' => 'change',
        'progress' => ['type' => 'none'],
      ],
      '#attributes' => ['class' => ['soc-list-action-item-wrapper']]
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
          '#markup' => $result['node']->get('field_reference_name')->value,
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
          '#attributes' => ['class' => ['wishlist-action-item', 'soc-list-action-item', 'soc-list-action-item-wrapper']]
        ];
        $i++;
      }
    }

    $form['wrapper_wishlist']['nb'] = ['#value' => $i];

    $confirmRemoveClass = 'confirm-remove';
    for ($i = 0; $i < 2; ++$i) {
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
        "#suffix" => '</div>',
        '#attributes' => ['target' => '_blank']
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
            'callback' => [$this, 'removeItems'],
            'wrapper' => 'wishlist_form_wrapper',
            'progress' => ['type' => 'none'],
          ],
        ];
        $form['actions']['others'][$i]['remove'][$i]['#attributes']['class'][] = $confirmRemoveClass;
      }
    }

    $this->contentListFormManager->attached($form, $confirmRemoveClass);

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
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function updateSession(array &$form, FormStateInterface $form_state) {
    return $this->contentListFormManager->updateSession($form, $form_state, 'wishlist_action_', 'socomec_wishlist_export');
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function removeItems(array &$form, FormStateInterface $form_state) {
    return $this->contentListFormManager->removeItems($form, $form_state,
      'wishlist_action_','socomec_wishlist_last_deleted','.soc-my-list-form-message',
      Url::fromRoute('soc_wishlist.undo_remove_item'),
      \Drupal::service('soc_wishlist.wishlist_manager')
    );
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
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
   * @return array|\Drupal\Core\StringTranslation\TranslatableMarkup|mixed|null
   */
  public static function getTitle() {
    $language = \Drupal::languageManager()->getCurrentLanguage();
    return \Drupal::config('soc_wishlist.settings')->get('page_title_' . $language->getId()) ?? t('My wishlist');
  }

}
