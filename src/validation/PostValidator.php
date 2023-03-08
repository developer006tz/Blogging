<?php

namespace App;

use App\{Post, Utils};

class PostValidator {
  public static function image($file, $required = false) {
    $errors = [];

    if (empty($file['name']) && $required) {
      array_push($errors, 'Image is required');
    }

    if (isset($file) && $file['size'] > 2000000) {
      array_push($errors, 'Maximum image size is 2MB');
    }

    return $errors;
  }

  public static function create($post, $file) {
    $errors = [];

    if (empty($post['title'])) {
      array_push($errors, 'Title is required');
    }

    $existingPost = Post::selectOne(['title' => $post['title']]);
    if (!empty($existingPost) && $existingPost['id'] != $post['id']) {
      array_push($errors, 'Title already exists.');
    }

    if (empty($post['body'])) {
      array_push($errors, 'Post body is required');
    }

    if (empty($post['topic_id'])) {
      array_push($errors, 'Topic is required');
    }

    $imageErrors = self::image($file, true);
    $errors = array_merge($errors, $imageErrors);
    return $errors;
  }

  public static function update($post, $file) {
    $errors = [];

    if (empty($post['title'])) {
      array_push($errors, 'Title is required');
    }

    $existingPost = Post::selectOne(['title' => $post['title']]);
    if (!empty($existingPost) && $existingPost['id'] != $post['id']) {
      array_push($errors, 'Title already exists.');
    }

    if (empty($post['body'])) {
      array_push($errors, 'Post body is required');
    }

    if (empty($post['topic_id'])) {
      array_push($errors, 'Topic is required');
    }

    $imageErrors = self::image($file, false);
    $errors = array_merge($errors, $imageErrors);
    return $errors;
  }
}