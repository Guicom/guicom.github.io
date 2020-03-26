<?php

namespace Drupal\soc_bookmarks\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_core\Service\MediaApi;
use Drupal\soc_bookmarks\Service\Manager\BookmarkManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;


class BookmarkEditForm extends FormBase {

  /** @var \Drupal\soc_bookmarks\Service\Manager\bookmarkManager $bookmarkManager */
  private $bookmarkManager;

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
   */
  public function __construct(BookmarkManager $bookmarkManager,
                              MessengerInterface $messenger,
                              MediaApi $mediaApi) {
    $this->bookmarkManager = $bookmarkManager;
    $this->messenger = $messenger;
    $this->mediaApi = $mediaApi;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_bookmarks.bookmark_manager'),
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
    return 'soc_bookmarks_bookmark_edit_form';
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
    $form['#theme'] = 'soc_bookmarks_custom_form';
    if (empty($this->bookmarkManager)) {
      $this->bookmarkManager = \Drupal::service('soc_bookmarks.bookmark_manager');
    }
    $items = $this->bookmarkManager->loadSavedItems();


    // Form wrapper for AJAX
    $form['wrapper_bookmark'] = [
      '#type' => 'container',
    ];

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

    $downloadList = (isset($_SESSION['socomec_bookmark_download'])) ? $_SESSION['socomec_bookmark_download'] : '';
    $i = 0;
    if (!empty($items) && is_array($items)) {
      foreach ($items as $result) {
        if ($result['node'] instanceof Node) {
          $nid = $result['node']->id();
          $title = $result['node']->getTitle();
          $type = $result['node']->get('field_res_resource_type')->entity->getName();;
          $form['wrapper_bookmark'][$i]['nid'] = [
            '#value' => $nid
          ];

          $form['wrapper_bookmark'][$i]['title_' . $nid] = [
            '#markup' => $title,
          ];

          $form['wrapper_bookmark'][$i]['type_' . $nid] = [
            '#markup' => $type,
          ];

          $default_value = [];
          if (!empty($downloadList[$nid])) {
            $default_value = [$nid];
          }
          $form['wrapper_bookmark'][$i]['bookmark_action_' . $nid] = [
            '#title' => 'action',
            '#title_display' => 'invisible',
            '#type' => 'checkboxes',
            '#options' => [$nid => ''],
            '#default_value' => $default_value,
            '#ajax' => [
              'callback' => [$this, 'updateSession'],
              'event' => 'change',
              'progress' => ['type' => 'none'],
            ],
            '#attributes' => [
              'class' => [
                'bookmark-action-item',
                'soc-list-action-item',
                'soc-list-action-item-wrapper'
              ]
            ]
          ];
          $i++;
        }
      }

      $form['wrapper_bookmark']['nb'] = [
        '#value' => $i
      ];

      $confirmRemoveClass = 'confirm-remove';
      for ($i = 0; $i < 2; ++$i) {
        // Update quantity
        if (sizeof($items)) {
          $form['actions']['others'][$i]['download'][$i] = [
            '#type' => 'link',
            '#title' => t('Download'),
            '#url' => Url::fromRoute('soc_bookmarks.download_items')
          ];

          $form['actions']['others'][$i]['remove'][$i] = [
            '#type' => 'submit',
            '#value' => t('Remove selected'),
            '#ajax' => [
              'callback' => [$this, 'removeItems'],
              'wrapper' => 'bookmark_form_wrapper',
              'progress' => ['type' => 'none'],
            ],
          ];
          $form['actions']['others'][$i]['remove'][$i]['#attributes']['class'][] = $confirmRemoveClass;
        }
      }

      $form['#attached']['library'][] = 'soc_content_list/list-actions';

      // Confirm before deleting
      $form['#attached']['library'][] = 'core/drupal.ajax';
      $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
      $form['#attached']['library'][] = 'soc_content_list/ajax-confirm';
      $form['#attached']['drupalSettings']['ajaxConfirm'][$confirmRemoveClass] = [
        'title' => $this->t('Confirm element removal'),
        'text' => $this->t('Are you sure that you want to remove this element?'),
        'buttons' => [
          'button_confirm' => ['text' => $this->t('Yes')],
          'button_reject' => ['text' => $this->t('No')],
        ]
      ];

      $form['#attached']['library'][] = 'soc_content_list/list-datatable';
      $form['#attached']['drupalSettings']['socListDatatable'] = [
        'searchPlaceholder' => $this->t('Search, filter...'),
      ];
    }
    else {
      $form['no_result'] = [
        '#markup' => \Drupal::config('soc_bookmarks.settings')->get('bookmark_no_result') ?? t('No result')
      ];
    }
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
        if (substr($keyItem, 0, 16) === "bookmark_action_") {
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
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function updateSession(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $input = $form_state->getUserInput();
    $items = self::customSelectedItems($input);
    $_SESSION['socomec_bookmark_download'] = [];
    if (sizeof($items)) {
      $_SESSION['socomec_bookmark_download'] = $items;
    }

    return $response;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function downloadItems(array &$form, FormStateInterface $form_state) {

  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function removeItems(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $input = $form_state->getUserInput();
    $items = self::customSelectedItems($input);
    if (sizeof($items)) {
      /** @var \Drupal\soc_bookmarks\Service\Manager\soc_bookmarks $soc_bookmarks */
      $bookmarkManager = \Drupal::service('soc_bookmarks.bookmark_manager');
      $bookmarkManager->loadSavedItems();
      $_SESSION['socomec_bookmark_last_deleted'] = [];
      foreach ($items as $deletedItem) {
        $removedLine = '#item_line_'.$deletedItem;
        $bookmarkManager->remove($deletedItem);
        try {
          $bookmarkManager->updateCookie();
          $response->addCommand(new RemoveCommand($removedLine));
          $_SESSION['socomec_bookmark_last_deleted'][$deletedItem] = $deletedItem;
        } catch (\Exception $e) {
          //$this->messenger->addError($e->getMessage());
        }
      }
      $count = sizeof($items);

      $url = Url::fromRoute('soc_bookmarks.undo_remove_item');
      $link = Link::fromTextAndUrl(t('Cancel deletion(s)'), $url)->toString();
      \Drupal::messenger()->addMessage($this->t("@count item(s) deleted. @link", ["@count" => $count , "@link" => $link ]), 'status', TRUE);

      $message = [
        '#theme' => 'status_messages',
        '#message_list' => drupal_get_messages(),
      ];

      $messages = \Drupal::service('renderer')->render($message);
      $response->addCommand(new PrependCommand('.soc-my-list-form-message', $messages));
    }

    return $response;
  }

  /**
   * @return array|\Drupal\Core\StringTranslation\TranslatableMarkup|mixed|null
   */
  public static function getTitle() {
    $language = \Drupal::languageManager()->getCurrentLanguage();
    return \Drupal::config('soc_bookmarks.settings')->get('bookmark_page_title')
      ?? t('My documents');
  }

}
