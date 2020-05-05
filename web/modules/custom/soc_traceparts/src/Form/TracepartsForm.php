<?php


namespace Drupal\soc_traceparts\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\soc_traceparts\Service\Manager\TracepartsUserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class TracepartsForm extends FormBase {

  /**
   * Tempstore service.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /** @var TracepartsUserManager $tracepartsUser */
  protected $tracepartsUser;

  /**
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempStoreFactory
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\soc_traceparts\Service\Manager\TracepartsUserManager $traceparts_user
   */
  public function __construct(PrivateTempStoreFactory $tempStoreFactory,
                              AccountInterface $current_user,
                              TracepartsUserManager $traceparts_user) {
    $this->tempStoreFactory = $tempStoreFactory;
    $this->currentUser = $current_user;
    $this->tracepartsUser = $traceparts_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('current_user'),
      $container->get('soc_traceparts.traceparts_user_manager')
    );
  }

}
