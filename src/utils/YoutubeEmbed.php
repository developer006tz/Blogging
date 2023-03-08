<?php

namespace App;

use App\{Utils};

class YoutubeEmbed {
  private static $beginningDelimiter = "[https://www.youtube.com/watch?";
  private static $endingDelimiter = "]";

  // Original source of this function implementation
  // can be found here: https://stackoverflow.com/a/8668959
  public static function extractVideoId($paramURL) {
   // we need a full url for the below functions to work normally
   $fullVideoUrl = self::$beginningDelimiter . $paramURL;
   parse_str(parse_url($fullVideoUrl, PHP_URL_QUERY), $my_array_of_vars);
   return $my_array_of_vars['v'];
  }

  public static function getEmbedHtmlString($stringWithVideoId) {
    $videoId = self::extractVideoId($stringWithVideoId);
    return '<iframe class="responsive-yt-video" src="https://www.youtube.com/embed/' . $videoId . '" allow="fullscreen;">
      </iframe>';
  }

  // The user submitted youtube video should exist in the post string
  // in the format: [https://www.youtube.com/watch?v=qfnbZOReFYs]
  public static function embedYoutubeVideos($postString) {
    $strArr = explode(self::$beginningDelimiter, $postString);
    $stringWithEmbed = "";

    foreach ($strArr as $value) {
      // if no delimiter, append the string as it is
      if (strpos($value, self::$endingDelimiter) === false) {
       $stringWithEmbed .= $value; 
      // else if there's a delimiter, insert an embed at that position
      } else {
        // this explode will split the string into two array elements:
        // first element will hold the string with video id (v=blahblah)
        // the second will hold a string. 
        $substrings = explode(self::$endingDelimiter, $value);

        // return an html string 
        $embedString = self::getEmbedHtmlString($substrings[0]);

        // Adding an empty string element at the beginning to ensure
        // that we have at least two elements in order for the join() to work
        // This also ensures the embeded video gets inserted at the beginning, as it should
        $substrings[0] = ' ';

        // Join the string and 
        $joinedStringIncludingEmbed = join($embedString, $substrings);
        $stringWithEmbed = $stringWithEmbed . $joinedStringIncludingEmbed;
      }
    }
    return $stringWithEmbed;
  }
}