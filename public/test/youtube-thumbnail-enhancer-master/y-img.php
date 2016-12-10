<?php
$youtubeId = $_GET['i'] ;

$url = 'http://img.youtube.com/vi/' . $youtubeId . '/0.jpg';
$img = dirname(__FILE__) . '/youtubeThumbnail_'  . $youtubeId . '.jpg';
echo "$img<br>$url";
#file_put_contents($img, file_get_contents($url));
file_get_contents("http://img.youtube.com/vi/$youtubeId/0.jpg");