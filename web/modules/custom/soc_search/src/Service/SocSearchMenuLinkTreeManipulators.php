<?php

namespace Drupal\soc_search\Service;

use Drupal\Core\Menu\DefaultMenuLinkTreeManipulators;
use Drupal\Core\Access\AccessManagerInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Menu\InaccessibleMenuLink;
use Drupal\menu_link_content\Plugin\Menu\MenuLinkContent;
use Drupal\Core\Entity\EntityRepository;

/**
 * Provides a couple of menu link tree manipulators.
 *
 * This class provides menu link tree manipulators to:
 * - Translated render item
 */
class SocSearchMenuLinkTreeManipulators extends DefaultMenuLinkTreeManipulators {

  /**
   * The access manager.
   *
   * @var \Drupal\Core\Access\AccessManagerInterface
   */
  protected $accessManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\EntityRepository
   */
  protected $entityRepository;

  /**
   * The current language ID.
   *
   * @var string
   */
  protected $langcode;

  /**
   * The list of available languages.
   *
   * @var array
   *    \Drupal\Core\Language\LanguageInterface[] An associative array of
   *    languages keyed by the language code.
   */
  protected $languages;

  /**
   * Drupal\Core\Entity\EntityStorageInterface.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   *    A storage instance.
   */
  protected $menuLinkContentStorage;

  /**
   * SocSearchMenuLinkTreeManipulators constructor.
   *
   * @param \Drupal\Core\Access\AccessManagerInterface $access_manager
   * @param \Drupal\Core\Session\AccountInterface $account
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Language\LanguageManager $language_manager
   * @param \Drupal\Core\Entity\EntityRepository $entity_repository
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(AccessManagerInterface $access_manager, AccountInterface $account, EntityTypeManagerInterface $entity_type_manager, LanguageManager $language_manager, EntityRepository $entity_repository) {
    $this->accessManager = $access_manager;
    $this->account = $account;
    $this->entityTypeManager = $entity_type_manager;
    $this->entityRepository = $entity_repository;
    $this->langcode = $language_manager->getCurrentLanguage()->getId();
    $this->languages = $language_manager->getLanguages();
    $this->menuLinkContentStorage = $this->entityTypeManager->getStorage('menu_link_content');
  }

  /**
   * Generates a unique index and sorts by it.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeElement[] $tree
   *   The menu link tree to manipulate.
   *
   * @return \Drupal\Core\Menu\MenuLinkTreeElement[]
   *   The manipulated menu link tree.
   */
  public function filterLanguage(array $tree) {
    foreach ($tree as $key => $element) {
      if ($element->link instanceof MenuLinkContent) {
        // Shortcut to the MenuLink item.
        $link = &$element->link;
        // Get the MenuLinkContent entity language.
        $link->langcode = $this->getLinkLanguage($link);
        // Test if the MenuLinkContent is the same language as the current.
        if ($this->langcode == $link->langcode) {
          $tree[$key]->access = parent::menuLinkCheckAccess($element->link);
        }
        else {
          $tree[$key]->link = new InaccessibleMenuLink($tree[$key]->link);
          $tree[$key]->access = AccessResult::forbidden();
          $tree[$key]->subtree = [];
        }

        // Filter also children items.
        if ($element->hasChildren) {
          $element->subtree = $this->filterByCurrentLanguage($element->subtree);
        }
      }
    }
    return $tree;
  }

  /**
   * Force the MenuLinkContent to tell us its language code.
   *
   * @param \Drupal\menu_link_content\Plugin\Menu\MenuLinkContent $link
   *   `The Menu Link Content entity.
   *
   * @return string
   *   The Menu Link Content entity's language ID.
   */
  protected function getLinkLanguage(MenuLinkContent $link) {
    $matadata = $link->getMetaData();
    $loaded_link = $this->menuLinkContentStorage->load($matadata['entity_id']);
    $loaded_lang_link = $this->entityRepository->getTranslationFromContext($loaded_link);

    return $loaded_lang_link->language()->getId();
  }
}
