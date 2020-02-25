<?php

namespace Drupal\soc_sales_locations\Form;

use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultForm.
 */
class ExportSampleFileSalesLocationsForm extends FormBase {

  // @todo: clic sur le bouton submit lance l'export CSV
  // @todo: ajouter une dépendance avec entity-manager
  // @todo: service pour avoir la liste de tous les locations
  // @todo: service pour générer un fichier CSV.
  // @todo: définir les données des nodes à extraire pour le fichier CSV.

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

  }

}
