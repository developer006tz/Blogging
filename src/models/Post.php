<?php

namespace App;

use App\{Topic, Utils};

class Post extends Db {
  static $tableName = 'posts';
  static $timestamps = true;
  static $fillable = ['user_id', 'topic_id', 'title', 'slug', 'body', 'image', 'published', 'view_count', 'deleted_at'];

  public static function search($queryString, $includeDrafts = true) {
    $searchQuery = "p.title LIKE CONCAT('%', :queryString, '%') 
                    OR p.body LIKE CONCAT('%', :queryString, '%') 
                    OR u.username LIKE CONCAT('%', :queryString, '%') 
                    OR t.name LIKE CONCAT('%', :queryString, '%')";
    $filterQuery = $includeDrafts ? "p.deleted_at IS NULL" : "p.deleted_at IS NULL AND p.published=1";

    $sql = "SELECT p.*, p.image as post_image, u.username, u.image as user_image, t.slug as topic_slug, t.name as topic_name FROM posts as p 
              LEFT JOIN users u ON p.user_id = u.id
              LEFT JOIN topics t ON p.topic_id = t.id 
              WHERE ($searchQuery) AND ($filterQuery)";

    $stmt = self::executeQuery($sql, ['queryString' => $queryString]);
    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function paginate($numberOfRecords = 10, $currentPage = 1) {
    $sql = "SELECT p.*, u.username, t.name as topic_name FROM posts as p 
              LEFT JOIN users u ON p.user_id = u.id
              LEFT JOIN topics t ON p.topic_id = t.id 
              WHERE p.deleted_at IS NULL ";
    $allTopicIds = Topic::getAllIds();

    if (isset($_SESSION['postsFilter'])) {
      if (in_array($_SESSION['postsFilter'], $allTopicIds)) {
        $sql = $sql . "AND p.topic_id=" . $_SESSION['postsFilter'] . " AND p.published=1 ORDER BY p.created_at DESC";
      } else {
        switch ($_SESSION['postsFilter']) {
          case 'NEWEST':
            $sql = $sql . "AND p.published=1 ORDER BY p.created_at DESC";
            break;
          case 'OLDEST':
            $sql = $sql . "AND p.published=1 ORDER BY p.created_at ASC";
            break;
          case 'POPULAR':
            $sql = $sql . "AND p.published=1 ORDER BY p.view_count DESC";
            break;
          case 'PUBLISHED':
            $sql = $sql . "AND p.published=1 ORDER BY p.created_at DESC";
            break;
          case 'DRAFTS':
            $sql = $sql . "AND p.published=0 ORDER BY p.created_at DESC";
            break;
          default:
            $sql = $sql . "ORDER BY p.created_at DESC";
            break;
        }
      }
    } else {
      $sql = $sql . "AND p.published=1 ORDER BY p.created_at DESC";
    }

    $countStmt = self::executeQuery($sql);
    $pagesCount = ceil($countStmt->rowCount() / $numberOfRecords);

    $sql = $sql . " LIMIT :currentPage,:numberOfRecords";
    $data = [
      'currentPage' => ($currentPage - 1) * $numberOfRecords,
      'numberOfRecords' => $numberOfRecords
    ];
    $stmt = self::executeQuery($sql, $data, false);
    $posts = $stmt->fetchAll();
    $pageNumbers = Utils::getPaginationLinks($currentPage, $pagesCount);

    return [
      'posts' => $posts,
      'numberPerPage' => $numberOfRecords,
      'pagesCount' => $pagesCount,
      'currentPage' => $currentPage,
      'pageNumbers' => $pageNumbers
    ];
  }

  public static function paginateTrashed($numberOfRecords = 10, $startAt = 1) {
    $conditionsString = "AND p.deleted_at IS NOT NULL";
    $postData = self::paginate($conditionsString, $numberOfRecords, $startAt);
    return $postData;
  }

  public static function togglePublished($post_id) {
    $post = self::selectOne(['id' => $post_id]);
    self::update($post_id, ['published' => $post['published'] ? 0 : 1]);
  }

  public static function getAllTrashed() {
    $sql = "SELECT p.*, u.username, t.name as topic_name FROM posts as p 
              LEFT JOIN users u ON p.user_id = u.id
              LEFT JOIN topics t ON p.topic_id = t.id WHERE deleted_at IS NOT NULL";

    $stmt = self::executeQuery($sql);
    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function getCollectionPosts($slug) {
    $sql = "SELECT p.id, p.title, fc.title AS collection_title, fc.slug as collection_slug
            FROM posts p INNER JOIN featured_collections fc 
            ON p.id = fc.post_id WHERE fc.slug=:slug";
    $stmt = self::executeQuery($sql, ['slug' => $slug]);
    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function getPostsByTopicSlug($slug, $currentPage = 1, $numberOfRecords = 3) {
    $sql = "SELECT p.*, p.image AS post_image, u.username, u.image AS user_image, t.name AS topic_name, t.slug AS topic_slug 
            FROM posts p INNER JOIN users u ON p.user_id = u.id 
            INNER JOIN topics t ON p.topic_id = t.id 
            WHERE t.slug=:slug AND p.published=1 AND p.deleted_at IS NULL ORDER BY p.created_at DESC LIMIT :currentPage,:numberOfRecords";
    $data = [
      'slug' => $slug,
      'currentPage' => ($currentPage - 1) * $numberOfRecords,
      'numberOfRecords' => $numberOfRecords,
    ];
    $stmt = self::executeQuery($sql, $data, false);
    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function selectAllPosts($currentPage = 1, $numberOfRecords = 3) {
    $sql = "SELECT p.*, p.image AS post_image, u.username, u.image AS user_image, t.name AS topic_name, t.slug AS topic_slug 
    FROM posts p INNER JOIN users u ON p.user_id = u.id 
    INNER JOIN topics t ON p.topic_id = t.id 
    WHERE p.published=1 AND p.deleted_at IS NULL ORDER BY p.created_at DESC LIMIT :currentPage,:numberOfRecords";
    $data = [
      'currentPage' => ($currentPage - 1) * $numberOfRecords,
      'numberOfRecords' => $numberOfRecords,
    ];
    $stmt = self::executeQuery($sql, $data, false);
    $posts = $stmt->fetchAll();
    return $posts; 
  }

  public static function getPostsByUserId($user_id, $currentPage = 1, $numberOfRecords = 3) {
    $sql = "SELECT p.*, p.image AS post_image, u.username, u.image AS user_image, t.name AS topic_name, t.slug AS topic_slug 
            FROM posts p INNER JOIN users u ON p.user_id = u.id 
            INNER JOIN topics t ON p.topic_id = t.id 
            WHERE u.id=:user_id AND p.published=1 AND p.deleted_at IS NULL ORDER BY p.created_at DESC LIMIT :currentPage,:numberOfRecords";
    $data = [
      'user_id' => $user_id,
      'currentPage' => ($currentPage - 1) * $numberOfRecords,
      'numberOfRecords' => $numberOfRecords,
    ];
    $stmt = self::executeQuery($sql, $data, false);
    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function getSinglePostBySlug($slug) {
    $sql = "SELECT p.*, p.image AS post_image, u.id AS u_id, u.username, u.bio, u.twitter, u.instagram, u.linkedin, u.id AS u_id, u.image AS user_image, t.name AS topic_name, t.slug AS topic_slug 
            FROM posts p INNER JOIN users u ON p.user_id = u.id 
            INNER JOIN topics t ON p.topic_id = t.id 
            WHERE p.slug=:slug AND p.published=1 AND p.deleted_at IS NULL LIMIT 1";
    $stmt = self::executeQuery($sql, ['slug' => $slug]);
    $post = $stmt->fetch();
    return $post;
  }

  public static function getRelatedPostsByPostId($post_id) {
    $sql = "SELECT p.*, p.image AS post_image, u.username, u.image AS user_image, rp.related_post_id as id 
            FROM posts p INNER JOIN users u ON p.user_id = u.id
            INNER JOIN related_posts rp ON rp.related_post_id = p.id 
            WHERE rp.post_id=:post_id AND p.published=1 AND p.deleted_at IS NULL ORDER BY rp.sort_order ASC";
    $stmt = self::executeQuery($sql, ['post_id' => $post_id]);
    $posts = $stmt->fetchAll();
    return $posts;
  }

  public static function incrementViews($post_id, $step = 1) {
    $sql = "UPDATE posts SET view_count=view_count + 1 WHERE id=:post_id";
    self::executeQuery($sql, ['post_id' => $post_id]);
  }

}
