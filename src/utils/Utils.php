<?php

namespace App;

class Utils {
    public static function d($var) {
        echo "<pre>", print_r($var, true), "</pre>";
    }

    public static function dd($var) {
        echo "<pre>", print_r($var, true), "</pre>";
        die();
    }

    public static $base_url = 'http://localhost/eveningclass101';

    public static function trimFormData($rawData) {
        $data = [];
        foreach ($rawData as $key => $value) {
            $shouldTrimValue = is_string($value) && !in_array($key, ['password', 'passwordConf', 'oldPassword']);
            $data[$key] = $shouldTrimValue ? trim($value) : $value;
        }

        return $data;
    }

    public static function getPaginationLinks($currentPage, $totalNumberOfPages) {
        $current = $currentPage;
        $last = $totalNumberOfPages;
        $delta = 2;
        $left = $current - $delta;
        $right = $current + $delta + 1;
        $range = array();
        $rangeWithDots = array();
        $l = -1;

        for ($i = 1; $i <= $last; $i++) {
            if ($i == 1 || $i == $last || $i >= $left && $i < $right) {
                array_push($range, $i);
            }
        }

        for ($i = 0; $i < count($range); $i++) {
            if ($l != -1) {
                if ($range[$i] - $l === 2) {
                    array_push($rangeWithDots, $l + 1);
                } else if ($range[$i] - $l !== 1) {
                    array_push($rangeWithDots, '...');
                }
            }
            array_push($rangeWithDots, $range[$i]);
            $l = $range[$i];
        }

        return $rangeWithDots;
    }

    public static function slugify($text, $divider = '-', $useLowerCase = true) {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = $useLowerCase ? strtolower($text) : $text;

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function fullUrl() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function estimateReadTime($text) {
        $plainText = strip_tags(html_entity_decode($text));
        $words = str_word_count($plainText);
        $minutes = ceil($words / 200);

        return $minutes . ' min';
    }

    public static function getPostBodyPreview($text, $length = 160) {
        return substr(strip_tags(html_entity_decode($text)), 0, 160);
    }

    public static function formattedPostFields($posts) {
        $formattedPosts = [];
        foreach ($posts as $post) {
            $formattedItem = $post;
            $formattedItem['post_image'] = '/assets/images/featured_images/' . $post['post_image'];
            $formattedItem['read_time'] = Utils::estimateReadTime($post['body']);
            $formattedItem['body_preview'] = Utils::getPostBodyPreview($post['body']);
            $formattedItem['user_image'] = '/assets/images/avatar/' . $post['user_image'];
            $formattedItem['username_slug'] = Utils::slugify($post['username'], '_', false);
            array_push($formattedPosts, $formattedItem);
        }
        return $formattedPosts;
    }

    public static function removePostBodyColumn($posts) {
        $postsWithoutBody = [];
        foreach ($posts as $post) {
            unset($post['body']);
            array_push($postsWithoutBody, $post);
        }
        return $postsWithoutBody;
    }
}
