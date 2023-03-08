<?php

namespace App;

use App\{Topic, Utils};

class TopicValidator {
  public static function create($topic) {
    $errors = [];

    if (empty($topic['name'])) {
      array_push($errors, 'Name is required');
    }

    $existingTopic = Topic::selectOne(['name' => $topic['name']]);
    if (!empty($existingTopic)) {
      array_push($errors, 'Name already exists.');
    }

    return $errors;
  }

  public static function update($topic) {
    $errors = [];

    if (empty($topic['name'])) {
      array_push($errors, 'Name is required');
    }

    $existingTopic = Topic::selectOne(['name' => $topic['name']]);
    if (!empty($existingTopic) && $existingTopic['id'] != $topic['id']) {
      array_push($errors, 'Name already exists.');
    }

    return $errors;
  }
}