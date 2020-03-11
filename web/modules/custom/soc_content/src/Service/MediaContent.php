<?php


namespace Drupal\soc_content\Service;


use Drupal\Core\Entity\EntityStorageException;
use Drupal\media\Entity\Media;
use Drupal\soc_content\Service\Manager\ContentManager;

class MediaContent extends ContentManager {

  /**
   * @param string $uuid
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  protected function getMediaByUuid(string $uuid) {
    return $this->getEntityByUuid('media', $uuid);
  }

  /**
   * Create new file.
   *
   * @param $source_file_path
   * @param $destination_file_name
   *
   * @return bool|\Drupal\file\Entity\File
   */
  public function createFile($source_file_path, $destination_file_name) {
    $file_data = file_get_contents($source_file_path);
    $destination_file_uri = 'public://' . $destination_file_name;
    $file = file_save_data($file_data, $destination_file_uri,
      \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
    try {
      $file->save();
      return $file;
    } catch (EntityStorageException $e) {
      $this->logger->error($e->getMessage());
    }
    return FALSE;
  }

  /**
   * Create new media.
   *
   * @param $data
   *
   * @return bool|\Drupal\media\Entity\Media
   */
  public function createMedia($data) {
    // Validate input.
    if (!isset($data['name'])) {
      $this->logger->warning('Trying to create a media without name, skipped...');
    }
    elseif (!isset($data['bundle'])) {
      $this->logger->warning('Trying to create a media without bundle, skipped...');
    }
    // If input is OK.
    else {
      // Check if media already exists.
      $medias = \Drupal::entityQuery('media')
        ->condition('name', $data['name'])
        ->condition('bundle', $data['bundle'])
        ->execute();

      // If media does not exist, create it.
      if (empty($medias)) {
        $newMedia = Media::create($data);
        try {
          $newMedia->save();
          return $newMedia;
        } catch (EntityStorageException $e) {
          $this->logger->error($e->getMessage());
        }
      }
    }
    return FALSE;
  }

}
