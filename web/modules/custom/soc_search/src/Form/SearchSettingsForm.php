<?php

namespace Drupal\soc_search\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Provides settings for soc_search module.
 */
class SearchSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'soc_search_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'soc_search.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $settings  = $this->config('soc_search.settings');

    $form['soc_search'] = [
      '#type'  => 'details',
      '#open'  => TRUE,
      '#title' => $this->t('Search - settings'),
    ];

    $form['soc_search']['basic'] = [
      '#type'  => 'details',
      '#open'  => TRUE,
      '#title' => $this->t('Basic'),
    ];

    $form['soc_search']['basic']['title'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Page title'),
      '#default_value' => $settings->get('title'),
      '#description' => $this->t('Title page without the word you are looking for.'),
    ];

    $form['soc_search']['basic']['title_searched'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Page title with searched text'),
      '#default_value' => $settings->get('title_searched'),
      '#description' => $this->t('Title page with the word you are looking for. '
        .'use @total and @WORD token for dynamic text exemple : @total results for "@word"'),
    ];

    $form['soc_search']['basic']['title_no_result'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Page title without result'),
      '#default_value' => $settings->get('title_no_result'),
      '#description' => $this->t('Title page without result.'),
    ];

    $form['soc_search']['basic']['breadcrumb_title'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Breadcrumb title'),
      '#default_value' => $settings->get('breadcrumb_title'),
      '#description' => $this->t('Breadcrumb of the search page without the word you are looking for.'),
    ];

    $form['soc_search']['basic']['breadcrumb_title_searched'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Breadcrumb title with searched text'),
      '#default_value' => $settings->get('breadcrumb_title_searched'),
      '#description' => $this->t('Breadcrumb of the search page with the word you are looking for. '
        .'use @word token for dynamic text exemple : Search results for "@word"'),
    ];


    $form['soc_search']['basic']['placeholder'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Placeholder'),
      '#default_value' => $settings->get('placeholder'),
    ];

    $form['soc_search']['top_search'] = [
      '#type'  => 'details',
      '#open'  => TRUE,
      '#title' => $this->t('Top Search'),
    ];

    $links_top_search = '<ul class="admin-list">';
    $links_top_search .= '<li><a href="/admin/structure/block/manage/top5searches" target="_blank">';
    $links_top_search .= '<span class="label">'.t("Top Search  Title block").'</span>';
    $links_top_search .= '<div class="description">'.t("Configure Block Top Search Title").'</div>';
    $links_top_search .= '</a></li>';
    $links_top_search .= '<li><a href="/admin/structure/menu/manage/search-top-search" target="_blank">';
    $links_top_search .= '<span class="label">'.t("Top Search settings").'</span>';
    $links_top_search .= '<div class="description">'.t("Configure Top searches links").'</div>';
    $links_top_search .= '</a></li>';
    $links_top_search .= '</ul>';
    $form['soc_search']['top_search']['top_search_number'] = [
      '#type'          => 'number',
      '#title' => $this->t('Maximum number of links in Top Search.'),
      '#default_value' => $settings->get('top_search_number'),
      '#suffix' => $links_top_search
    ];

    $form['soc_search']['quick_links'] = [
      '#type'  => 'details',
      '#open'  => TRUE,
      '#title' => $this->t('Quick Links'),
    ];

    $links_quick_links = '<ul class="admin-list">';
    $links_quick_links .= '<li><a href="/admin/structure/block/manage/quicklinks" target="_blank">';
    $links_quick_links .= '<span class="label">'.t("Quicklink Title block").'</span>';
    $links_quick_links .= '<div class="description">'.t("Configure Block Quicklink Title").'</div>';
    $links_quick_links .= '</a></li>';
    $links_quick_links .= '<li><a href="/admin/structure/menu/manage/search-quick-link" target="_blank">';
    $links_quick_links .= '<span class="label">'.t("Quick Links settings").'</span>';
    $links_quick_links .= '<div class="description">'.t("Configure Quick Links").'</div>';
    $links_quick_links .= '</a></li>';
    $links_quick_links .= '</ul>';
    $form['soc_search']['quick_links']['quick_links_number'] = [
      '#type'          => 'number',
      '#title' => $this->t('Maximum number of links in Quick Links.'),
      '#default_value' => $settings->get('quick_links_number'),
      '#suffix' => $links_quick_links
    ];

    $form['soc_search']['autocomplete'] = [
      '#type'  => 'details',
      '#open'  => TRUE,
      '#title' => $this->t('Autocomplete'),
    ];

    $form['soc_search']['autocomplete']['suggestion_title'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Suggestion title'),
      '#default_value' => $settings->get('suggestion_title'),
    ];

    $form['soc_search']['autocomplete']['categorized_title'] = [
      '#type'          => 'textfield',
      '#title' => $this->t('Categorized title'),
      '#default_value' => $settings->get('categorized_title'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save settings.
    $settings = $this->configFactory->getEditable('soc_search.settings');
    $settings->set('title', $form_state->getValue('title'))->save();
    $settings->set('title_searched', $form_state->getValue('title_searched'))->save();
    $settings->set('title_no_result', $form_state->getValue('title_no_result'))->save();
    $settings->set('breadcrumb_title', $form_state->getValue('breadcrumb_title'))->save();
    $settings->set('breadcrumb_title_searched', $form_state->getValue('breadcrumb_title_searched'))->save();
    $settings->set('placeholder', $form_state->getValue('placeholder'))->save();
    $settings->set('top_search_number', $form_state->getValue('top_search_number'))->save();
    $settings->set('quick_links_number', $form_state->getValue('quick_links_number'))->save();
    $settings->set('suggestion_title', $form_state->getValue('suggestion_title'))->save();
    $settings->set('categorized_title', $form_state->getValue('categorized_title'))->save();
  }

}
