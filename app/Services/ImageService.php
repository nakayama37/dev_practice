<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService

{
  /**
   * 画像のストレージ保存
   * @param  $imageFile, $folderName
   * @return $fileNameToStore
   */
  public static function upload($imageFile, $folderName) 
  {
      $fileName = uniqid(rand() . '_');
      $extention = $imageFile->extension();
      $fileNameToStore = $fileName . '.' . $extention;
      Storage::putFileAs('public/' . $folderName . '/' , $imageFile, $fileNameToStore);

      return $fileNameToStore;
  }
}
