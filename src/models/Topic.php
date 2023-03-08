<?php

namespace App;

class Topic extends Db {
  static $tableName = 'topics';
  static $timestamps = true;
  static $fillable = ['name', 'slug', 'description'];

  public static function selectWithPostsCount() {
    $sql = "SELECT t.*, COUNT(p.id) AS post_count FROM topics t LEFT JOIN posts p ON p.topic_id = t.id GROUP BY t.id";
    $stmt = self::executeQuery($sql);
    $topics = $stmt->fetchAll();
    return $topics;
  }

  public static function getAllIds() {
    $sql = "SELECT id FROM " . self::$tableName;
    $stmt = self::executeQuery($sql);
    $topicIds = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
    return $topicIds;
  }
}
