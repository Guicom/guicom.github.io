<?php


namespace Drupal\soc_nextpage\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\file\Entity\File;

/**
 * Class DefaultImageSettings
 *
 * @package Drupal\soc_nextpage\Form
 */
class DefaultImageSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'product_default_image_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'soc_nextpage.product_default_config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('soc_nextpage.product_default_config');

    $form['default_image_product'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Default image product'),
      '#upload_location' => 'public://default_img',
      '#default_value'  => $config->get('default_image_product' ?? ''),
    ];
    $form['default_image_reference'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Default image reference'),
      '#upload_location' => 'public://default_img',
      '#default_value'  => $config->get('default_image_reference' ?? ''),
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
        $file->save();
      }

      $this->configFactory->getEditable('soc_nextpage.product_default_config')
        ->set($configKey, $form_state->getValue($configKey))
        ->save();
    }

    parent::submitForm($form, $form_state);
  }

}
