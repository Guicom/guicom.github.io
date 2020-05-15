<?php

namespace Drupal\soc_blocks_promotion\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BlockPromotionEntityController.
 *
 *  Returns responses for Block promotion entity routes.
 */
class BlockPromotionEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Block promotion entity revision.
   *
   * @param int $block_promotion_entity_revision
   *   The Block promotion entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($block_promotion_entity_revision) {
    $block_promotion_entity = $this->entityTypeManager()->getStorage('block_promotion_entity')
      ->loadRevision($block_promotion_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('block_promotion_entity');

    return $view_builder->view($block_promotion_entity);
  }

  /**
   * Page title callback for a Block promotion entity revision.
   *
   * @param int $block_promotion_entity_revision
   *   The Block promotion entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($block_promotion_entity_revision) {
    $block_promotion_entity = $this->entityTypeManager()->getStorage('block_promotion_entity')
      ->loadRevision($block_promotion_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $block_promotion_entity->label(),
      '%date' => $this->dateFormatter->format($block_promotion_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Block promotion entity.
   *
   * @param \Drupal\soc_blocks_promotion\Entity\BlockPromotionEntityInterface $block_promotion_entity
   *   A Block promotion entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BlockPromotionEntityInterface $block_promotion_entity) {
    $account = $this->currentUser();
    $block_promotion_entity_storage = $this->entityTypeManager()->getStorage('block_promotion_entity');

    $langcode = $block_promotion_entity->language()->getId();
    $langname = $block_promotion_entity->language()->getName();
    $languages = $block_promotion_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $block_promotion_entity->label()]) : $this->t('Revisions for %title', ['%title' => $block_promotion_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all block promotion entity revisions") || $account->hasPermission('administer block promotion entity entities')));
    $delete_permission = (($account->hasPermission("delete all block promotion entity revisions") || $account->hasPermission('administer block promotion entity entities')));

    $rows = [];

    $vids = $block_promotion_entity_storage->revisionIds($block_promotion_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\soc_blocks_promotion\BlockPromotionEntityInterface $revision */
      $revision = $block_promotion_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $block_promotion_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.block_promotion_entity.revision', [
            'block_promotion_entity' => $block_promotion_entity->id(),
            'block_promotion_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $block_promotion_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.block_promotion_entity.translation_revert', [
                'block_promotion_entity' => $block_promotion_entity->id(),
                'block_promotion_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.block_promotion_entity.revision_revert', [
                'block_promotion_entity' => $block_promotion_entity->id(),
                'block_promotion_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.block_promotion_entity.revision_delete', [
                'block_promotion_entity' => $block_promotion_entity->id(),
                'block_promotion_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['block_promotion_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public static function clone($block_promotion_entity) {
    if (!empty($block_promotion_entity)) {
      $entity_storage = \Drupal::entityTypeManager()->getStorage('block_promotion_entity');
      $entity = $entity_storage->load($block_promotion_entity);
      $idRedirect = $block_promotion_entity;
    }
    if (!empty($entity)) {
      $cloneEntity = $entity->createDuplicate();
      $cloneEntity->set('name', 'clone '. $entity->getName());
      try {
        $cloneEntity->save();
        $idRedirect = $cloneEntity->id();
      }
      catch (\Exception $e) {}
    }

    if (!empty($idRedirect)) {
      $redirect_url = Url::fromRoute('entity.block_promotion_entity.edit_form', ['block_promotion_entity' => $idRedirect]);
    }
    else {
      $redirect_url = Url::fromRoute('entity.block_promotion_entity.add_page');
    }
    // This is where you set the destination.
    $response = new RedirectResponse($redirect_url->toString(), Response::HTTP_TEMPORARY_REDIRECT);
    return $response;
  }

}
