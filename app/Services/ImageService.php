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
    try {

      $fileName = uniqid(rand() . '_');
      $extention = $imageFile->extension();
      $fileNameToStore = $fileName . '.' . $extention;
      Storage::putFileAs('public/' . $folderName . '/' , $imageFile, $fileNameToStore);

      return $fileNameToStore;
  
    } catch (Exception  $e) {

      Log::error('画像アップロードエラー: ' . $e->getMessage());
      return back()->withErrors(['image' => '画像のアップロードに失敗しました。']);

    }
  }
  /**
   * 画像のストレージ編集
   * @param  $imageFile, $folderName
   * @return $fileNameToStore
   */
  public static function update($imageFile, $folderName, $imagePath) 
  {
    try {
      // 古い画像を削除
      if ($imagePath) {
        Storage::delete('public/' . $folderName . '/' . $imagePath);
      }
      // 新しい画像を保存
      $fileName = uniqid(rand() . '_');
      $extention = $imageFile->extension();
      $fileNameToStore = $fileName . '.' . $extention;
      Storage::putFileAs('public/' . $folderName . '/' , $imageFile, $fileNameToStore);

      return $fileNameToStore;

    } catch (Exception  $e) {

      Log::error('画像アップロードエラー: ' . $e->getMessage());
      return back()->withErrors(['image' => '画像のアップロードに失敗しました。']);
      
    }

  }
}
