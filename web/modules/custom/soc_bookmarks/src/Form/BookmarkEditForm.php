<?php

namespace Drupal\soc_bookmarks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_core\Service\MediaApi;
use Drupal\soc_bookmarks\Service\Manager\BookmarkManager;
use \Drupal\soc_content_list\Service\Manager\ContentListFormManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;


class BookmarkEditForm extends FormBase {

  /** @var \Drupal\soc_bookmarks\Service\Manager\bookmarkManager $bookmarkManager */
  private $bookmarkManager;

  /** @var \Drupal\soc_content_list\Service\Manager\ContentListFormManager $contentListFormManager */
  private $contentListFormManager;

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
   * BookmarkEditForm constructor.
   *
   * @param \Drupal\soc_bookmarks\Service\Manager\BookmarkManager $bookmarkManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\soc_core\Service\MediaApi $mediaApi
   * @param \Drupal\soc_content_list\Service\Manager\ContentListFormManager $contentListFormManager
   */
  public function __construct(BookmarkManager $bookmarkManager,
                              MessengerInterface $messenger,
                              MediaApi $mediaApi,
                              ContentListFormManager $contentListFormManager) {
    $this->bookmarkManager = $bookmarkManager;
    $this->messenger = $messenger;
    $this->mediaApi = $mediaApi;
    $this->contentListFormManager = $contentListFormManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_bookmarks.bookmark_manager'),
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
    if (!empty($items)) {
      usort($items, function ($item1, $item2) {
        return $item2['timestamp'] <=> $item1['timestamp'];
      });
    }
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
          $form['actions']['others'][$i]['remove'][$i]['#attributes']['class'][] = 'confirm-remove';
        }
      }
      if (empty($this->contentListFormManager)) {
        $this->contentListFormManager = \Drupal::service('soc_content_list.content_list_form_manager');
      }
      $this->contentListFormManager->attached($form, 'confirm-remove');
    }
    else {
      $form['wrapper_bookmark']['no_result'] = [
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
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function updateSession(array &$form, FormStateInterface $form_state) {
    return $this->contentListFormManager->updateSession($form, $form_state, 'bookmark_action_', 'socomec_bookmark_download');
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
    $emptyMessage = \Drupal::config('soc_bookmarks.settings')->get('bookmark_no_result') ?? t('No results.');
    return $this->contentListFormManager->removeItems(
      $form,
      $form_state,
      'bookmark_action_',
      'socomec_bookmark_last_deleted',
      '.soc-my-list-form-message',
      $emptyMessage,
      Url::fromRoute('soc_bookmarks.undo_remove_item'),
      \Drupal::service('soc_bookmarks.bookmark_manager')
      );
  }

  /**
   * @return array|\Drupal\Core\StringTranslation\TranslatableMarkup|mixed|null
   */
  public static function getTitle() {
    return \Drupal::config('soc_bookmarks.settings')->get('bookmark_page_title') ?? t('My documents');
  }

}
