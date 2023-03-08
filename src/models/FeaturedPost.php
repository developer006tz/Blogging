<?php

namespace App;

use App\{Utils};

class FeaturedPost extends Db {
  static $tableName = 'featured_posts';
  static $timestamps = true;
  static $fillable = ['post_id'];

  public static function getFeaturedPost() {
    $sql = "SELECT p.*, p.image AS post_image, u.username, u.image AS user_image, t.name AS topic_name, t.slug AS topic_slug FROM posts p 
            INNER JOIN featured_posts fp ON p.id = fp.post_id
            INNER JOIN topics t ON p.topic_id = t.id
            INNER JOIN users u ON p.user_id=u.id LIMIT 1";
    $stmt = self::executeQuery($sql);
    $post = $stmt->fetch();
    $post['read_time'] = Utils::estimateReadTime($post['body']);
    $post['preview'] = Utils::getPostBodyPreview($post['body'], 160);
    return $post;
  }

}
