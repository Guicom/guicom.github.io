<?php

namespace Drupal\soc_store_locator\Plugin\views\argument_validator;

use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Annotation\ViewsArgumentValidator;
use Drupal\views\Plugin\views\argument_validator\Entity;
use Drupal\taxonomy\Plugin\views\argument_validator\TermName;

/**
 * Validates whether a term name is a valid term argument.
 *
 * @ViewsArgumentValidator(
 *   id = "country_term_name",
 *   title = @Translation("Taxonomy term alias into ID"),
 *   entity_type = "taxonomy_term"
 * )
 */
class CountryTermName extends TermName {

  /**
   * {@inheritdoc}
   */
  public function validateArgument($argument) {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $system_path = \Drupal::service('path.alias_manager')->getPathByAlias('/' . $argument, $language);
    $path = explode("/", $system_path);
    $argument = end($path);

    // If bundles is set then restrict the loaded terms to the given bundles.
    if (!empty($this->options['bundles'])) {
      $terms = $this->termStorage->loadByProperties(['tid' => $argument, 'vid' => $this->options['bundles']]);
    }
    else {
      $terms = $this->termStorage->loadByProperties(['tid' => $argument]);
    }

    // $terms are already bundle tested but we need to test access control.
    foreach ($terms as $term) {
      if ($this->validateEntity($term)) {
        // We only need one of the terms to be valid, so set the argument to
        // the term ID return TRUE when we find one.
        $this->argument->argument = $term->id();
        return TRUE;
        // @todo: If there are other values in $terms, maybe it'd be nice to
        // warn someone that there were multiple matches and we're only using
        // the first one.
      }
    }
    return FALSE;
  }


}
