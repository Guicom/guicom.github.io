<?php

namespace Drupal\soc_sales_locations\Form;

use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\soc_core\Service\MediaApi;
use Drupal\soc_sales_locations\Service\SalesLocationsManagerServiceInterface;
use Drupal\soc_wishlist\Service\Manager\WishlistManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DefaultForm.
 */
class ExportSampleFileSalesLocationsForm extends FormBase {

  // @todo: clic sur le bouton submit lance l'export CSV
  // @todo: ajouter une dépendance avec entity-manager
  // @done: service pour avoir la liste de tous les locations
  // @todo: service pour générer un fichier CSV.
  // @todo: définir les données des nodes à extraire pour le fichier CSV.


  /**
   * @var \Drupal\soc_sales_locations\Service\SalesLocationsManagerServiceInterface
   */
  private $manager;

  /**
   * ExportSampleFileSalesLocationsForm constructor.
   *
   * @param \Drupal\soc_sales_locations\Service\SalesLocationsManagerServiceInterface $manager
   */
  public function __construct(SalesLocationsManagerServiceInterface $manager) {
    $this->manager = $manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('soc_sales_locations.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'export_file_sales_locations_forms';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    \Drupal::service('soc_sales_locations.manager')->getNodes();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addStatus('Liste des nodes');
    \Drupal::service('soc_sales_locations.manager')->getNodes();

  }

}
