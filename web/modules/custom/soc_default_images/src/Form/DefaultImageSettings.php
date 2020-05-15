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

    $product_uuid = $config->get('default_image_product');
    $default_image_product = '';
    if (!is_null($product_uuid)) {
      try {
        $default_image_product = $this->entityRepository->loadEntityByUuid('file', $product_uuid)->id();
      } catch (EntityStorageException $e) {
      }
    }

    $form['default_image_product'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Default image product'),
      '#upload_location' => 'public://',
      '#default_value'  => $default_image_product ? [$default_image_product] : NULL,
    ];

    $reference_uuid = $config->get('default_image_reference');
    $default_image_reference = '';
    if (!is_null($reference_uuid)) {
      try {
        $default_image_reference = $this->entityRepository->loadEntityByUuid('file', $reference_uuid)->id();
      }
      catch (EntityStorageException $e) {
      }
    }

    $form['default_image_reference'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Default image reference'),
      '#upload_location' => 'public://',
      '#default_value'  => $default_image_reference ? [$default_image_reference] : NULL,
    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    foreach ([
               'default_image_product',
               'default_image_reference',
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
