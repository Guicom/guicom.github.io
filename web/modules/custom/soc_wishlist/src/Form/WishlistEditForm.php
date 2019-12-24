<?php

namespace Drupal\soc_wishlist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\node\Entity\Node;
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
   * WishlistController constructor.
   *
   * @param \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager
   */
  public function __construct(WishlistManager $wishlistManager, MessengerInterface $messenger) {
    $this->wishlistManager = $wishlistManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soc_wishlist.wishlist_manager'),
      $container->get('messenger')
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
    // Get data from cookie.
    $data = [
      'R_22004110' => [
        'extid' => 'R_22004110',
        'quantity' => 1,
      ],
      'R_22003110' => [
        'extid' => 'R_22003110',
        'quantity' => 3,
      ],
      'R_22004016' => [
        'extid' => 'R_22004016',
        'quantity' => 2,
      ],
    ];
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
        $options[$result['node']->id()] = [
          'picture' => $result['node']->get('field_reference_picture')->target_id,
          'reference' => $result['node']->get('field_reference_ref')->value,
          'description' => $result['node']->getTitle(),
          'quantity' => [
            'data' => [
              '#type' => 'number',
              '#title' => 'Quantity',
              '#title_display' => 'invisible',
              '#value' => $result['quantity'],
              '#name' => 'quantity[' . $extId . ']',
              '#size' => 2,
            ],
          ],
        ];
      }
    }

    // Create fields.
    $form['items'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
      '#empty' => t('No items found!'),
      '#prefix' => '<div>',
      '#suffix' => '</div>',
    ];

    // Link
    $form['links'] = [
      '#type' => 'item',
      '#markup' => '<a href="/wishlist/export/csv">CSV</a> | <a href="/wishlist/export/xls">XLS</a> | <a href="/wishlist/export/xlsx">XLSX</a> | <a href="/wishlist/export/pdf">PDF</a>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Remove selected'),
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
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $test = true;
  }
}
