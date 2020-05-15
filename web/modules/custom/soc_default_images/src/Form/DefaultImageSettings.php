<?php


namespace Drupal\soc_default_images\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DefaultImageSettings
 *
 * @package Drupal\soc_nextpage\Form
 */
class DefaultImageSettings extends ConfigFormBase {

  const DIS_SETTINGS_KEY = 'soc_default_images.socomec_default_image_form';

  /** @var \Drupal\Core\Entity\EntityRepository $entityRepository */
  protected $entityRepository;

  public function __construct(ConfigFactoryInterface $config_factory,
                              EntityRepository $entity_repository) {
    parent::__construct($config_factory);
    $this->entityRepository = $entity_repository;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'socomec_default_image_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      self::DIS_SETTINGS_KEY,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::DIS_SETTINGS_KEY);
    $items = [
      'nodes' => [
        'label' =>  t('Default image by node entity bundle'),
        'fields' =>  [
          'default_image_product' => t('Default image product'),
          'default_image_reference' => t('Default image reference'),
        ]
      ],
      'block_promotion_entity' => [
        'label' =>  t('Default image by blocks promo entity bundle'),
        'fields' =>  [
          'default_image_bloc_promo_offer' => t('Default image bloc promo offer'),
          'default_image_bloc_promo_digital_asset' => t('Default image bloc promo digital asset'),
          'default_image_bloc_promo_toolbox' => t('Default image bloc promo toolbox'),
          'default_image_bloc_promo_custom' => t('Default image bloc promo custom'),
        ]
      ],
    ];

    foreach ($items as $key_item => $item) {
      $form[$key_item] = [
        '#type' => 'details',
        '#open' => TRUE,
        '#title' => $item['label'] ? $item['label'] : NULL,
      ];
      foreach ($item['fields'] as $key => $label) {
        $uuid = $config->get($key);
        $default_image = '';
        if (!is_null($uuid)) {
          try {
            $default_image = $this->entityRepository->loadEntityByUuid('file', $uuid)->id();
          } catch (EntityStorageException $e) {}
        }
        $form[$key_item][$key] = [
          '#type' => 'managed_file',
          '#title' => $label ? $label : NULL,
          '#upload_location' => 'public://',
          '#default_value'  => $default_image ? [$default_image] : NULL,
        ];
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    foreach ([
               'default_image_product',
               'default_image_reference',
               'default_image_bloc_promo_offer',
               'default_image_bloc_promo_digital_asset',
               'default_image_bloc_promo_toolbox',
               'default_image_bloc_promo_custom',
             ] as $configKey) {

      $form_file = $form_state->getValue($configKey, 0);
      if (isset($form_file[0]) && !empty($form_file[0])) {
        $file = File::load($form_file[0]);
        $file->setPermanent();
        try {
          $file->save();
        } catch (EntityStorageException $e) {
        }
        $this->configFactory->getEditable(self::DIS_SETTINGS_KEY)
          ->set($configKey, $file->uuid())
          ->save();
      }
    }

    parent::submitForm($form, $form_state);
  }

}
