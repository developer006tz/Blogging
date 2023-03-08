<?php

$base_url = 'http://localhost/eveningclass101';

define("ROOT_PATH", realpath(dirname(__FILE__)));
define("BASE_URL", $base_url);
define("ASSETS_URL", $base_url . '/assets');

function url($path = '/') {
    echo BASE_URL . $path;
}

function assets($path = '/') {
    echo ASSETS_URL . $path;
}

function d($var) {
    echo "<pre>", print_r($var, true), "</pre>";
}

function dd($var) {
    echo "<pre>", print_r($var, true), "</pre>";
    die();
}
