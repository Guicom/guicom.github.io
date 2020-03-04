<?php
/**
 * @file
 * Contains \Drupal\soc_eu_cookie_compliance\Plugin\Field\FieldFormatter\socGprdLinkFormatter.
 */

namespace Drupal\soc_eu_cookie_compliance\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use \Drupal\soc_eu_cookie_compliance\Cache\Context\SocomecEccCacheContext;

/**
 * Plugin implementation of the 'soc_gpdr_link' formatter.
 *
 * @FieldFormatter(
 *   id = "soc_gpdr_link",
 *   label = @Translation("Link GPDR Formatter"),
 *   field_types = {
 *     "link"
 *   },
 * )
 */

class SocGprdLinkFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = t('Implement categorie eu cookie compliance.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'soc_ecc_categorie' => '',
      ] + parent::defaultSettings();
  }


  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    $form['soc_ecc_categorie'] = [
      '#type' => 'textfield',
      '#title' => t('Eu cookie compliance categorie'),
      '#default_value' => $this->getSetting('soc_ecc_categorie') ?: '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

      $element = [];

      foreach ($items as $delta => $item) {
        // By default use the full URL as the link text.
        $url = $item->getUrl() ?: Url::fromRoute('<none>');
        $link_title = $url->toString();
        if (!empty($this->getSetting('soc_ecc_categorie'))) {
          $soc_ecc_service = \Drupal::service('soc_eu_cookie_compliance.soc_ecc');
          $soc_ecc_service->setCategorie($this->getSetting('soc_ecc_categorie'));
          $element[$delta] = [
            '#type' => 'link',
            '#title' => $link_title,
            '#options' => $url->getOptions(),
            '#cache' => [
              'contexts' => [SocomecEccCacheContext::CONTEXT_ID]
            ],
          ];

          if (!$soc_ecc_service->hasAccess() && $soc_ecc_service->getMessage()) {
            $element[$delta]['#message'] = $soc_ecc_service->getMessage();
          }
          else {
            $element[$delta]['#url'] = $url;
          }
          $element['#cache']['contexts'] = [SocomecEccCacheContext::CONTEXT_ID];
        }
        else {
          $element[$delta] = [
            '#type' => 'link',
            '#title' => $link_title,
            '#options' => $url->getOptions()
          ];
        }
      }
      return $element;
    }
}
