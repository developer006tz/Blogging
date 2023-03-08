<?php

namespace App;

use App\{Utils};

class Collection extends Db {
  static $tableName = 'featured_collections';
  static $timestamps = true;
  static $fillable = ['title', 'slug', 'post_id'];

  public static function getUniqueCollections() {
    $sql = "SELECT DISTINCT title, slug FROM " . self::$tableName;
    $stmt = self::executeQuery($sql);
    $collections = $stmt->fetchAll();
    return $collections;
  }

}
