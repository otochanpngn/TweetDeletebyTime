<?php

// OAuth���C�u�����̓ǂݍ���
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

//�F�؏��S��
$consumerKey = "cPvQyxtJwqUbcjN6jHIfeiD6y";
$consumerSecret = "OiSqE6Ktj6iAj7aet0Zu1wg8oc95yRPlZqHJqDM6vbW4BIWNOR";
$accessToken = "3145082738-vx0VPX31Ip48mSSjqynIEqW2U7Zm8ysOGlyM2Me";
$accessTokenSecret = "1FAn6a0F5HVZKLG2jh7Ofy26qyrQphWnyEC6uGWnLXPhe";

//�ڑ�
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

//�Ώۂ̃��[�U�[
$username = "TweetTimeDelete";

$timeline = $connection->get("statuses/user_timeline", array("screen_name" => $username));

print_r($timeline);

$tweetid = "585860718865489920";

print_r($tweet->entities->hashtags);

function destroytweet($id,$connection,$username){
	print "statuses/destroy/{$id}";
	$connection->post("statuses/destroy/{$id}", array("screen_name" => $username));
	//post("statuses/destroy/{$id}", array("screen_name" => $username));
}

//destroytweet($tweetid,$connection,$username);

//$res = $connection->post("statuses/destroy", array("status" => "test2"));

?>