<?php

namespace Drupal\soc_core\Service;

use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

class MediaApi {

  public function getFileIdFromMediaId($media_id) {
    if ($media = Media::load($media_id)) {
      /** @var \Drupal\media\MediaSourceInterface $mediaSource */
      if ($mediaSource = $media->getSource()) {
        $fid = $mediaSource->getSourceFieldValue($media);
        return $fid;
      }
    }
    return FALSE;
  }

  public function getFileUriFromMediaId($media_id) {
    if ($fid = $this->getFileIdFromMediaId($media_id)) {
      $file = File::load($fid);
      if(!empty($file)){
        return $file->getFileUri();
      }
    }
    return FALSE;
  }

  public function getFileUrlFromMediaId($media_id) {
    if ($fid = $this->getFileIdFromMediaId($media_id)) {
      $file = File::load($fid);
      if(!empty($file)){
        return file_create_url($file->getFileUri());
      }
    }
    return FALSE;
  }

}
