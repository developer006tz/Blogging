<?php

namespace App;

use App\{Utils};

class PostCollection extends Db {
  static $tableName = 'post_collections';
  static $timestamps = true;
  static $fillable = ['title', 'slug', 'description', 'position'];

  public static function getPostsByCollectionId($id, $detailedFields, $currentPage = 1, $numberOfRecords = 3) {
    $sql = "";
    if ($detailedFields) {
      $sql = "SELECT p.*, p.image AS post_image, u.username, u.image AS user_image, t.name AS topic_name, t.slug AS topic_slug, pcp.sort_order 
              FROM posts p INNER JOIN users u ON p.user_id = u.id 
              INNER JOIN topics t ON p.topic_id = t.id 
              INNER JOIN post_collection_posts pcp ON p.id = pcp.post_id 
              WHERE pcp.collection_id=:id AND p.published=1 AND p.deleted_at IS NULL ORDER BY pcp.sort_order ASC LIMIT :currentPage,:numberOfRecords";
      $data = [
        'id' => $id,
        'currentPage' => ($currentPage - 1) * $numberOfRecords,
        'numberOfRecords' => $numberOfRecords,
      ];
      $stmt = self::executeQuery($sql, $data, false);
    } else {
      $sql = "SELECT p.id, p.title, pcp.sort_order FROM posts p INNER JOIN post_collection_posts pcp 
              ON p.id = pcp.post_id WHERE pcp.collection_id=:id AND p.published=1 AND p.deleted_at IS NULL ORDER BY pcp.sort_order ASC";
      $stmt = self::executeQuery($sql, ['id' => $id]);
    }

    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function getFeaturedCollections() {
    $sql = "SELECT * FROM post_collections ORDER BY position ASC LIMIT 4";
    $stmt = self::executeQuery($sql);
    $firstFourCollections = $stmt->fetchAll();
    
    $featuredPostCollections = [];
    foreach ($firstFourCollections as $key => $collection) {
      $collection['posts'] = self::getPostsByCollectionId($collection['id'], true, 1, 6);
      array_push($featuredPostCollections, $collection);
    }

    return $featuredPostCollections;
  }

  public static function withPostsCount() {
    $sql = "SELECT pc.*, COUNT(pcp.post_id) AS post_count FROM post_collections pc 
            LEFT JOIN post_collection_posts pcp ON pc.id = pcp.collection_id GROUP BY pc.id ORDER BY pc.position ASC";

    $stmt = self::executeQuery($sql);
    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function getBestCollections() {
    $sql = "SELECT * FROM post_collections WHERE title LIKE CONCAT('%', :queryString, '%') ORDER BY title DESC";
    $stmt = self::executeQuery($sql, ['queryString' => 'Best of']);
    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function removePosts($collection_id) {
    $sql = "DELETE FROM post_collection_posts WHERE collection_id=:collection_id";
    self::executeQuery($sql, ['collection_id' => $collection_id]);
  }

  public static function addPost($newRecord) {
    $sql = "INSERT INTO post_collection_posts SET collection_id=:collection_id, post_id=:post_id, sort_order=:sort_order";
    self::executeQuery($sql, $newRecord);
  }
}
