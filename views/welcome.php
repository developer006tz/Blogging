<?php

require_once __DIR__ . '/../vendor/autoload.php';
use App\{User, Post, Utils};

include 'partials/message.php';

// Utils::dd($this->users);

foreach ($this->users as $key => $user) {
  echo $user['username'] . '--';
}

echo "Welcome to my project";
