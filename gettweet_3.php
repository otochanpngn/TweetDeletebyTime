<?php

// OAuthライブラリの読み込み
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

date_default_timezone_set('Asia/Tokyo');

//認証情報４つ
$consumerKey = "cPvQyxtJwqUbcjN6jHIfeiD6y";
$consumerSecret = "OiSqE6Ktj6iAj7aet0Zu1wg8oc95yRPlZqHJqDM6vbW4BIWNOR";
$accessToken = "3145082738-vx0VPX31Ip48mSSjqynIEqW2U7Zm8ysOGlyM2Me";
$accessTokenSecret = "1FAn6a0F5HVZKLG2jh7Ofy26qyrQphWnyEC6uGWnLXPhe";

//接続
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$username = "TweetTimeDelete";

$timeline = $connection->get("statuses/user_timeline", array("screen_name" => $username));

foreach($timeline as $tweet){
	
	//ハッシュタグの取得
	//print_r($tweet->entities->hashtags[0]->text."\n");
	//print_r($tweet->entities->hashtags[0]);
	if(!empty($tweet->entities->hashtags[0]->text)){

		//ハッシュタグの表示
		$hashtagtweet = $tweet->entities->hashtags[0]->text;
		print("ハッシュタグの内容：".$hashtagtweet."\n");

		//ハッシュタグの長さを取得
		$hashtagtweet_long = strlen($hashtagtweet);

		//ハッシュタグの最後の文字を取得
		$hashtagtweet_unit = substr($hashtagtweet, -1,1);
		if($hashtagtweet_unit == "m"){
			print("ハッシュタグの最後の文字はmです\n");
		}
		elseif($hashtagtweet_unit == "h"){
			print("ハッシュタグの最後の文字はhです\n");
		}
		print("ハッシュタグの最後の文字列：".$hashtagtweet_unit."\n");

		//ハッシュタグの最後の文字以外を取得
		$hashtagtweet_time = substr($hashtagtweet, 0, $hashtagtweet_long - 1 );
		print("ハッシュタグの最後の文字以外：".$hashtagtweet_time."\n");

		//投稿時間取得
		$tweettime = date("Y/m/d H:i:s", strtotime((string) $tweet->created_at));
		print("投稿時間：".$tweettime."\n");

		//投稿時間をタイムスタンプ化
		$tweettime_Y = date("Y", strtotime((string) $tweet->created_at));
		$tweettime_m = date("m", strtotime((string) $tweet->created_at));
		$tweettime_d = date("d", strtotime((string) $tweet->created_at));
		$tweettime_H = date("H", strtotime((string) $tweet->created_at));
		$tweettime_i = date("i", strtotime((string) $tweet->created_at));
		$tweettime_s = date("s", strtotime((string) $tweet->created_at));
		$tweettime2 = mktime($tweettime_H,$tweettime_i,$tweettime_s,$tweettime_m,$tweettime_d,$tweettime_Y);
		print("タイムスタンプ：".$tweettime2."\n\n");
	}
	//print_r($tweet->entities->hashtags);
	//print_r($tweet->created_at);

}

//現在時刻取得
$nowtime = date("現在時間とタイムスタンプ\nH:i:s\n");
print($nowtime);

//現在時刻をタイムスタンプ化
$nowtime2 = time($nowtime);
print($nowtime2."\n");


//比較する対象の投稿時間は一番古いツイートのみ（forreachの最後のtweettime2のみ有効）
//投稿時間と現在時刻の差分をタイムスタンプから求める(単位は秒)
$time_different = ($nowtime2-$tweettime2);
print("投稿からの経過時間:".$time_different."秒\n");

//投稿時間からの経過時間を計算
$hour = floor($time_different/3600);
$time_different -= ($hour*3600);
$min = floor($time_different/60);
$time_different -= ($min*60);
$sec = $time_different;

print("投稿からの経過時間:".$hour."h".$min."min".$sec."sec");



//$res = $connection->post("statuses/destroy", array("status" => "test2"));

?>