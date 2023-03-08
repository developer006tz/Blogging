<?php

namespace App;

use App\{Utils};

class RelatedPost extends Db {
  static $tableName = 'related_posts';
  static $timestamps = true;
  static $fillable = ['post_id', 'related_post_id', 'sort_order'];

}
