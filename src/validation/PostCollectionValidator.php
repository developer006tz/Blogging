<?php

namespace App;

use App\{PostCollection, Utils};

class PostCollectionValidator {
  public static function create($data) {
    $errors = [];
    
    if (empty($data['title'])) {
      \array_push($errors, 'Title of collection is required');
    }
    
    if (empty($data['description'])) {
      \array_push($errors, 'Description is required');
    }

    return $errors;
  }

  public static function update($data) {
    $errors = [];

    if (empty($data['title'])) {
      array_push($errors, 'Title is required');
    }

    $existingTopic = PostCollection::selectOne(['title' => $data['title']]);
    if (!empty($existingTopic) && $existingTopic['id'] != $data['id']) {
      array_push($errors, 'Title already exists.');
    }

    return $errors;
  }
}