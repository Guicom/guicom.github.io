<?php

namespace Drupal\soc_search\Controller;

use Drupal\search_api_autocomplete\Controller\AutocompleteController;
use Drupal\Core\Render\RendererInterface;
use Drupal\search_api\SearchApiException;
use Drupal\search_api_autocomplete\SearchApiAutocompleteException;
use Drupal\search_api_autocomplete\SearchInterface;
use Drupal\search_api_autocomplete\Utility\AutocompleteHelperInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Transliteration\TransliterationInterface;

/**
 * Provides a controller for autocompletion.
 */
class SocSearchAutocompleteController extends AutocompleteController {
  /**
   * Page callback: Retrieves autocomplete suggestions.
   *
   * @param \Drupal\search_api_autocomplete\SearchInterface $search_api_autocomplete_search
   *   The search for which to retrieve autocomplete suggestions.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The autocompletion response.
   */
  public function autocomplete(SearchInterface $search_api_autocomplete_search, Request $request) {
    $json = parent::autocomplete($search_api_autocomplete_search, $request);
    $content = $json->getContent();
    $matches = ['suggestion', 'categorized'];
    $matches['suggestion'] = json_decode($content, true);

    $index = \Drupal\search_api\Entity\Index::load('global_search');
    $fulltext_fields = $index->getFulltextFields();
    /** @var \Drupal\search_api\Query\QueryInterface $query */
    $query = $index->query();
    $query->setFulltextFields($fulltext_fields);
    $parse_mode = \Drupal::service('plugin.manager.search_api.parse_mode')->createInstance('direct');
    $query->setParseMode($parse_mode);
    $query->keys($request->query->get('q'));
    $query->sort('search_api_relevance', 'ASC');
    $query->setOption( 'search_api_retrieved_field_values', ['tcngramm_X3b_en_title', 'ss_type']);
    $query->range(0, 7);
    $results_set = $query->execute();
    $nb_results = $results_set->getResultCount();
    // Récupération des entités
    $bundle_info = \Drupal::service("entity_type.bundle.info")->getAllBundleInfo();
    foreach ($results_set->getResultItems() as $item) {
      $resultat = $item->getOriginalObject()->getValue();
      $matches['categorized'][] = [
        'bundle' => $bundle_info[$resultat->getEntityTypeId()][$resultat->bundle()]['label'],
        'value' => $resultat->getTitle(),
        'url' => $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$resultat->id()),
        'label' => $resultat->getTitle(),
      ];
    }

    return new JsonResponse($matches);
  }

}
