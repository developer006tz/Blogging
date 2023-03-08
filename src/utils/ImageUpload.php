<?php

namespace App;

class ImageUpload {
  static $avatarDir = 'assets/images/avatar/';
  static $featuredImagesDir = 'assets/images/featured_images/';
  static $inPostImagesDir = 'assets/images/in_post_images/';

  public static function avatar($file, $filename) {
    $targetFile = self::$avatarDir . $filename;
    return \move_uploaded_file($file['tmp_name'], $targetFile);
  }

  public static function featuredImage($file, $filename) {
    $targetFile = self::$featuredImagesDir . $filename;
    return \move_uploaded_file($file['tmp_name'], $targetFile);
  }
  
  public static function inPostImage($file, $filename) {
    $targetFile = self::$inPostImagesDir . $filename;
    return \move_uploaded_file($file['tmp_name'], $targetFile);
  }
}