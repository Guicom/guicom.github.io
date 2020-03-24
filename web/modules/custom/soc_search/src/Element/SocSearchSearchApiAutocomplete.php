<?php

namespace Drupal\soc_search\Element;

use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Url;
use Drupal\search_api_autocomplete\Element\SearchApiAutocomplete;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_api_autocomplete\Entity\Search;

/**
 * Provides a Search API Autocomplete form element.
 *
 * @FormElement("soc_search_search_api_autocomplete")
 */
class SocSearchSearchApiAutocomplete extends SearchApiAutocomplete {

  /**
   * Adds autocomplete functionality to elements.
   *
   * This sets up autocomplete functionality for elements with an
   * #autocomplete_route_name property, using the #autocomplete_route_parameters
   * property if present.
   *
   * For example, suppose your autocomplete route name is
   * 'mymodule.autocomplete' and its path is
   * '/mymodule/autocomplete/{a}/{b}'. In a form array, you would create a text
   * field with properties:
   * @code
   * '#autocomplete_route_name' => 'mymodule.autocomplete',
   * '#autocomplete_route_parameters' => array('a' => $some_key, 'b' => $some_id),
   * @endcode
   * If the user types "keywords" in that field, the full path called would be:
   * 'mymodule_autocomplete/$some_key/$some_id?q=keywords'
   *
   * @param array $element
   *   The form element to process. Properties used:
   *   - #autocomplete_route_name: A route to be used as callback URL by the
   *     autocomplete JavaScript library.
   *   - #autocomplete_route_parameters: The parameters to be used in
   *     conjunction with the route name.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param array $complete_form
   *   The complete form structure.
   *
   * @return array
   *   The form element.
   */
  public static function processAutocomplete(&$element, FormStateInterface $form_state, &$complete_form) {
    $url = NULL;
    $access = FALSE;

    if (!empty($element['#autocomplete_route_name'])) {
      $parameters = isset($element['#autocomplete_route_parameters']) ? $element['#autocomplete_route_parameters'] : [];
      $url = Url::fromRoute($element['#autocomplete_route_name'], $parameters)->toString(TRUE);
      /** @var \Drupal\Core\Access\AccessManagerInterface $access_manager */
      $access_manager = \Drupal::service('access_manager');
      $access = $access_manager->checkNamedRoute($element['#autocomplete_route_name'], $parameters, \Drupal::currentUser(), TRUE);
    }

    if ($access) {
      $metadata = BubbleableMetadata::createFromRenderArray($element);
      if ($access->isAllowed()) {
        $element['#attributes']['class'][] = 'soc-search-form-autocomplete';
        $metadata->addAttachments(['library' => ['soc_search/soc_search.autocomplete']]);
        // Provide a data attribute for the JavaScript behavior to bind to.
        $element['#attributes']['data-autocomplete-path'] = $url->getGeneratedUrl();
        $metadata = $metadata->merge($url);
      }
      $metadata
        ->merge(BubbleableMetadata::createFromObject($access))
        ->applyTo($element);
    }

    return $element;
  }

  /**
   * Adds Search API Autocomplete functionality to a form element.
   *
   * @param array $element
   *   The form element to process. Properties used:
   *   - #search_id: The entity ID of the Search config entity.
   *   - #additional_data: (optional) Additional data to pass to the
   *     autocomplete callback as GET parameters.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param array $complete_form
   *   The complete form structure.
   *
   * @return array
   *   The processed form element.
   *
   * @throws \InvalidArgumentException
   *   Thrown if the #search_id property is missing or invalid.
   */
  public static function processSearchApiAutocomplete(array &$element, FormStateInterface $form_state, array &$complete_form) {

    parent::processSearchApiAutocomplete($element,  $form_state,  $complete_form);

    if (!empty ($element['#autocomplete_route_parameters'])) {
      $element['#autocomplete_route_name'] = 'soc_search.autocomplete';
    }

    return $element;
  }

}
