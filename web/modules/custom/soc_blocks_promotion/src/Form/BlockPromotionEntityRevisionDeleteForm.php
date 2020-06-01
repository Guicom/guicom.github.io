<?php

namespace Drupal\soc_blocks_promotion\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Block promotion entity revision.
 *
 * @ingroup soc_blocks_promotion
 */
class BlockPromotionEntityRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Block promotion entity revision.
   *
   * @var \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface
   */
  protected $revision;

  /**
   * The Block promotion entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $blockPromotionEntityStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->blockPromotionEntityStorage = $container->get('entity_type.manager')->getStorage('block_promotion_entity');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'block_promotion_entity_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => format_date($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.block_promotion_entity.version_history', ['block_promotion_entity' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $block_promotion_entity_revision = NULL) {
    $this->revision = $this->BlockPromotionEntityStorage->loadRevision($block_promotion_entity_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->BlockPromotionEntityStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Block promotion entity: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Block promotion entity %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.block_promotion_entity.canonical',
       ['block_promotion_entity' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {block_promotion_entity_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.block_promotion_entity.version_history',
         ['block_promotion_entity' => $this->revision->id()]
      );
    }
  }

}
