<?php

// OAuthƒ‰ƒCƒuƒ‰ƒŠ‚Ì“Ç‚Ýž‚Ý
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

date_default_timezone_set('Asia/Tokyo');

//”FØî•ñ‚S‚Â
$consumerKey = "cPvQyxtJwqUbcjN6jHIfeiD6y";
$consumerSecret = "OiSqE6Ktj6iAj7aet0Zu1wg8oc95yRPlZqHJqDM6vbW4BIWNOR";
$accessToken = "3145082738-vx0VPX31Ip48mSSjqynIEqW2U7Zm8ysOGlyM2Me";
$accessTokenSecret = "1FAn6a0F5HVZKLG2jh7Ofy26qyrQphWnyEC6uGWnLXPhe";

//Ú‘±
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$username = "TweetTimeDelete";

$timeline = $connection->get("statuses/user_timeline", array("screen_name" => $username));

foreach($timeline as $tweet){
	//echo "$i: $tweet->text" . PHP_EOL;
	//print_r($tweet->entities->hashtags);
	//print_r($tweet->created_at);
	$datetime = date("H:i:s", strtotime((string) $tweet->created_at));
	print_r($datetime);
	print("\n\n");
}

$nowtime = date("\nH:i:s\n");
print($nowtime);

$time = $nowtime - $datetime;
$result = gmdate("H:i:s", $time) ;
print($result);


//$res = $connection->post("statuses/destroy", array("status" => "test2"));

?>