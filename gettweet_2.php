<?php

// OAuth���C�u�����̓ǂݍ���
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

date_default_timezone_set('Asia/Tokyo');

//�F�؏��S��
$consumerKey = "cPvQyxtJwqUbcjN6jHIfeiD6y";
$consumerSecret = "OiSqE6Ktj6iAj7aet0Zu1wg8oc95yRPlZqHJqDM6vbW4BIWNOR";
$accessToken = "3145082738-vx0VPX31Ip48mSSjqynIEqW2U7Zm8ysOGlyM2Me";
$accessTokenSecret = "1FAn6a0F5HVZKLG2jh7Ofy26qyrQphWnyEC6uGWnLXPhe";

//�ڑ�
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$username = "TweetTimeDelete";

$timeline = $connection->get("statuses/user_timeline", array("screen_name" => $username));

foreach($timeline as $tweet){
	//echo "$i: $tweet->text" . PHP_EOL;
	//print_r($tweet->entities->hashtags);
	//print_r($tweet->created_at);
	//���e���Ԏ擾
	$tweettime = date("Y/m/d H:i:s", strtotime((string) $tweet->created_at));
	print($tweettime."\n");

	//���e���Ԃ��^�C���X�^���v��
	$tweettime_Y = date("Y", strtotime((string) $tweet->created_at));
	$tweettime_m = date("m", strtotime((string) $tweet->created_at));
	$tweettime_d = date("d", strtotime((string) $tweet->created_at));
	$tweettime_H = date("H", strtotime((string) $tweet->created_at));
	$tweettime_i = date("i", strtotime((string) $tweet->created_at));
	$tweettime_s = date("s", strtotime((string) $tweet->created_at));
	$tweettime2 = mktime($tweettime_H,$tweettime_i,$tweettime_s,$tweettime_m,$tweettime_d,$tweettime_Y);
	print($tweettime2);
	print("\n\n");
}

//���ݎ����擾
$nowtime = date("���ݎ��Ԃƃ^�C���X�^���v\nH:i:s\n");
print($nowtime);

//���ݎ������^�C���X�^���v��
$nowtime2 = time($nowtime);
print($nowtime2."\n");


//��r����Ώۂ̓��e���Ԃ͈�ԌÂ��c�C�[�g�̂݁iforreach�̍Ō��tweettime2�̂ݗL���j
//���e���Ԃƌ��ݎ����̍������^�C���X�^���v���狁�߂�(�P�ʂ͕b)
$time_different = ($nowtime2-$tweettime2);
print("���e����̌o�ߎ���:".$time_different."�b\n");

//���e���Ԃ���̌o�ߎ��Ԃ��v�Z
$hour = floor($time_different/3600);
$time_different -= ($hour*3600);
$min = floor($time_different/60);
$time_different -= ($min*60);
$sec = $time_different;

print("���e����̌o�ߎ���:".$hour."h".$min."min".$sec."sec");



//$res = $connection->post("statuses/destroy", array("status" => "test2"));

?>