<?php

namespace Drupal\soc_content_hub_server\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ChannelsController extends ControllerBase {

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  public function __construct(LanguageManagerInterface $language_manager) {
    $this->languageManager = $language_manager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\Core\Controller\ControllerBase|\Drupal\soc_content_hub\Controller\ChannelsController
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('language_manager')
    );
  }

  public function generateChannels() {
    // Set entity types
    $entityTypes = [
      'node' => [
        'news',
        'event',
        'landing_page',
        'thank_you_page',
      ],
      'media' => [
        'image',
        'file',
        'video',
        'remote_video',
      ],
    ];

    // Get languages
    $languages = $this->languageManager->getLanguages();

    // Load models
    try {
      $nodeModelChannel = \Drupal::entityTypeManager()
        ->getStorage('channel')
        ->load('en_news');
    } catch (\Exception $e) {
      \Drupal::logger('soc_content_hub_server')->error($e->getMessage());
    }
    try {
      $mediaModelChannel = \Drupal::entityTypeManager()
        ->getStorage('channel')
        ->load('en_image');
    } catch (\Exception $e) {
      \Drupal::logger('soc_content_hub_server')->error($e->getMessage());
    }
    if (!$nodeModelChannel || !$mediaModelChannel) {
      return [];
    }

    // Load existing channels
    $existingChannels = \Drupal::entityTypeManager()
      ->getStorage('channel')
      ->loadMultiple();

    // Create config items
    foreach ($languages as $language) {
      $languageId = $language->getId();
      $configLanguageId = str_replace('-', '_', $languageId);
      // Nodes
      foreach ($entityTypes['node'] as $entityType) {
        $channelId = $configLanguageId . '_' . $entityType;
        if (!array_key_exists($channelId, $existingChannels)) {
          /** @var Drupal\entity_share_server\Entity\Channel $newChannel */
          $newChannel = $nodeModelChannel->createDuplicate();
          $newChannel->set('id', $channelId);
          $newChannel->set('langcode', 'en');
          $newChannel->set('label', strtoupper($languageId) . ' / ' . ucfirst($entityType));
          $newChannel->set('channel_langcode', $languageId);
          $newChannel->set('channel_bundle', $entityType);
          try {
            $newChannel->save();
          } catch (\Exception $e) {
            \Drupal::logger('soc_content_hub')->error($e->getMessage());
          }
        }
        else {
          /** @var Drupal\entity_share_server\Entity\Channel $newChannel */
          $newChannel = $existingChannels[$channelId];
          $newChannel->set('langcode', 'en');
          $newChannel->set('label', strtoupper($languageId) . ' / ' . ucfirst($entityType));
          $newChannel->set('channel_langcode', $languageId);
          $newChannel->set('channel_bundle', $entityType);
          try {
            $newChannel->save();
          } catch (\Exception $e) {
            \Drupal::logger('soc_content_hub')->error($e->getMessage());
          }
        }
      }
      // Media
      foreach ($entityTypes['media'] as $entityType) {
        $channelId = $configLanguageId . '_' . $entityType;
        if (!array_key_exists($channelId, $existingChannels)) {
          /** @var Drupal\entity_share_server\Entity\Channel $newChannel */
          $newChannel = $mediaModelChannel->createDuplicate();
          $newChannel->set('id', $channelId);
          $newChannel->set('langcode', 'en');
          $newChannel->set('label', strtoupper($languageId) . ' / ' . ucfirst($entityType));
          $newChannel->set('channel_langcode', $languageId);
          $newChannel->set('channel_bundle', $entityType);
          try {
            $newChannel->save();
          } catch (\Exception $e) {
            \Drupal::logger('soc_content_hub')->error($e->getMessage());
          }
        }
        else {
          /** @var Drupal\entity_share_server\Entity\Channel $newChannel */
          $newChannel = $existingChannels[$channelId];
          $newChannel->set('langcode', 'en');
          $newChannel->set('label', strtoupper($languageId) . ' / ' . ucfirst($entityType));
          $newChannel->set('channel_langcode', $languageId);
          $newChannel->set('channel_bundle', $entityType);
          try {
            $newChannel->save();
          } catch (\Exception $e) {
            \Drupal::logger('soc_content_hub')->error($e->getMessage());
          }
        }
      }
    }
  }

}