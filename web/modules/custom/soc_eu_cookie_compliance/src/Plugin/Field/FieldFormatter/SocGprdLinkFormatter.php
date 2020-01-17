<?php
/**
 * @file
 * Contains \Drupal\soc_eu_cookie_compliance\Plugin\Field\FieldFormatter\socGprdLinkFormatter.
 */

namespace Drupal\soc_eu_cookie_compliance\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\link\LinkItemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Plugin implementation of the 'soc_gpdr_link' formatter.
 *
 * @FieldFormatter(
 *   id = "soc_gpdr_link",
 *   label = @Translation("GPDR Formatter"),
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
    $summary[] = $this->t('Implement categorie eu cookie compliance.');
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
    $form['soc_ecc_categorie'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Eu cookie compliance categorie'),
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
      $soc_ecc_service = \Drupal::service('soc_eu_cookie_compliance.soc_ecc');
      $soc_ecc_service->setCategorie($this->getSetting('soc_ecc_categorie'));
      $element[$delta] = [
        '#type' => 'link',
        '#title' => $link_title,
        '#options' => $url->getOptions(),
        '#cache' => [
          'max-age' => 0
        ]
      ];

      if ($soc_ecc_service->hasAccess()) {
        $element[$delta]['#url'] = $url;
      }
      else {
        $element[$delta]['#message'] = $soc_ecc_service->getMessage();
      }
    }

    $elements['#cache']['contexts'] = ['socomec_ecc_gpdr'];

    return $element;
  }
}
