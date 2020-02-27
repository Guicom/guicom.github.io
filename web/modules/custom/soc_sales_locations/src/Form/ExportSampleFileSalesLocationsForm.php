<?php

namespace Drupal\soc_sales_locations\Form;

use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\soc_sales_locations\Service\SalesLocationsManagerServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultForm.
 */
class ExportSampleFileSalesLocationsForm extends FormBase {

  // @todo: clic sur le bouton submit lance l'export CSV
  // @done: ajouter une dépendance avec entity-manager
  // @done: service pour avoir la liste de tous les locations
  // @done: service pour générer un fichier CSV.
  // @todo: définir les données des nodes à extraire pour le fichier CSV.
  // @see: \Drupal\soc_wishlist\Service\Manager\WishlistExport::exportCSV()

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
