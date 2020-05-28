<?php


namespace Drupal\soc_content\Service;


use Drupal\Core\Entity\EntityStorageException;
use Drupal\file\Entity\File;
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
   * @param $file_name
   * @param $destination_file_name
   * @param null $uuid
   *
   * @return bool|\Drupal\file\Entity\File
   */
  public function createFile($file_name, $destination_file_name = NULL, $uuid = NULL) {
    // Check if file already exists, return it then.
    if (!is_null($uuid)) {
      $filesQuery = \Drupal::entityQuery('file');
      $filesQuery->condition('uuid', $uuid);
      $files = $filesQuery->execute();
      if (!empty($files)) {
        $fid = reset($files);
        return File::load($fid);
      }
    }

    // If file does not exist, create it.
    $file_data = file_get_contents('../content/images/' . $file_name);
    if (is_null($destination_file_name)) {
      $destination_file_name = $file_name;
    }
    $destination_file_uri = 'public://' . $destination_file_name;
    $file = file_save_data($file_data, $destination_file_uri,
      \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
    if (!is_null($uuid)) {
      $file->set('uuid', $uuid);
    }
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
   * @param $file
   * @param $name
   * @param $bundle
   * @param array $data
   *
   * @return bool|\Drupal\media\Entity\Media
   */
  public function createMedia(File $file, string $name, string $bundle, array $data = []) {
    $fieldName = $this->getFileFieldName($bundle);
    if (!empty($fieldName)) {
      $data[$fieldName] = $file->id();
      $data['name'] = $name;
      $data['bundle'] = $bundle;
      // Check if media already exists.
      $mediaQuery = \Drupal::entityQuery('media');
      if (isset($data['uuid'])) {
        $mediaQuery->condition('uuid', $data['uuid']);
      }
      else {
        $mediaQuery->condition('name', $data['name']);
        $mediaQuery->condition('bundle', $data['bundle']);
      }
      $medias = $mediaQuery->execute();

      // If media does not exist, create it.
      if (empty($medias)) {
        return $this->createEntity('Drupal\media\Entity\Media', $data);
      }
    }
    else {
      $this->logger->error('Media bundle @bundle not found!', [
        '@bundle' => $bundle,
      ]);
    }
    return FALSE;
  }

  /**
   * @param $media_type
   *
   * @return string
   */
  private function getFileFieldName($media_type) {
    $fieldName = '';
    switch ($media_type) {
      case 'audio':
        $fieldName = 'field_media_audio_file';
        break;
      case 'file':
      case 'pdf':
        $fieldName = 'field_media_file';
        break;
      case 'icone':
      case 'image':
        $fieldName = 'field_media_image';
        break;
      case 'private_file':
        $fieldName = 'field_media_file_1';
        break;
      case 'private_image':
        $fieldName = 'field_media_image_1';
        break;
      case 'product_image':
        $fieldName = 'field_media_image_2';
        break;
      case 'remote_video':
        $fieldName = 'field_media_oembed_video';
        break;
      case 'video':
        $fieldName = 'field_media_video_file';
        break;
      default:
        break;
    }
    return $fieldName;
  }

}
