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

$destory_tweet_id = array();
$destory_tweet_time = array();
$destory_tweet_count = array();

//ツイートを削除する関数
function destorytweet($id,$connection,$username){
	//print "statuses/destroy/{$id}";
	$connection->post("statuses/destroy/{$id}", array("screen_name" => $username));
	//post("statuses/destroy/{$id}", array("screen_name" => $username));
}

foreach($timeline as $tweet){

	//投稿時間取得
	$tweettime = date("Y/m/d H:i:s", strtotime((string) $tweet->created_at));
	print("\n投稿時間：".$tweettime."\n");

	//投稿時間をタイムスタンプ化
	$tweettime_Y = date("Y", strtotime((string) $tweet->created_at));
	$tweettime_m = date("m", strtotime((string) $tweet->created_at));
	$tweettime_d = date("d", strtotime((string) $tweet->created_at));
	$tweettime_H = date("H", strtotime((string) $tweet->created_at));
	$tweettime_i = date("i", strtotime((string) $tweet->created_at));
	$tweettime_s = date("s", strtotime((string) $tweet->created_at));
	$tweettime2 = mktime($tweettime_H,$tweettime_i,$tweettime_s,$tweettime_m,$tweettime_d,$tweettime_Y);
	//print("タイムスタンプ：".$tweettime2."\n\n");
	
	//ハッシュタグの取得
	//print_r($tweet->entities->hashtags[0]->text."\n");
	//print_r($tweet->entities->hashtags[0]);
	if(!empty($tweet->entities->hashtags[0]->text)){

		//ハッシュタグの取得
		$hashtagtweet = $tweet->entities->hashtags[0]->text;

		//ハッシュタグの長さを取得
		$hashtagtweet_long = strlen($hashtagtweet);

		//ハッシュタグの最後の文字を取得
		$hashtagtweet_unit = substr($hashtagtweet, -1,1);

		//ハッシュタグの最後の文字以外を取得
		$hashtagtweet_time = substr($hashtagtweet, 0, $hashtagtweet_long - 1 );

		//ハッシュタグの最後の文字が"m"か"h"を判定
		if($hashtagtweet_unit == "m" || $hashtagtweet_unit == "ｍ"){

			print("ハッシュタグの内容：".$hashtagtweet."\n");
			print("ハッシュタグの最後の文字はmです\n");
			print("ハッシュタグの最後の文字以外：".$hashtagtweet_time."\n");

			//ハッシュタグの最後の文字以外が数字であるか判別
			if(ctype_digit($hashtagtweet_time)){

				print("時間後に削除する対象です\n");

				//ハッシュタグの最後の文字以外を整数に変換
				$destory_time = intval($hashtagtweet_time);
				//整数を対応する数値に変換
				$count = $destory_time * 60;

				//削除対象のツイートID取得
				$tweet_id = $tweet->id;

				//削除対象のツイートIDと削除時間を配列に追加
				$destory_tweet_id[] = $tweet_id;
				$destory_tweet_time[] = $tweettime2;
				$destory_tweet_count[] = $count;

				if(is_int($destory_time)){
					print("削除対象のID:".$tweet_id."\n");
					print("ツイート削除まで".$destory_time."分\n");
				}
			}
			else{
				print("時間後に削除する対象ではありません\n");
			}
		}
		elseif($hashtagtweet_unit == "h" || $hashtagtweet_unit == "ｈ"){

			print("ハッシュタグの内容：".$hashtagtweet."\n");
			print("ハッシュタグの最後の文字はhです\n");
			print("ハッシュタグの最後の文字以外：".$hashtagtweet_time."\n");

			//ハッシュタグの最後の文字以外が数字であるか判別

			if(ctype_digit($hashtagtweet_time)){

				print("時間後に削除する対象です\n");

				//ハッシュタグの最後の文字以外を整数に変換
				$destory_time = intval($hashtagtweet_time);
				//整数を対応する数値に変換
				$count = $destory_time * 3600;

				//削除対象のツイートID取得
				$tweet_id = $tweet->id;

				//削除対象のツイートIDと削除時間を配列に追加
				$destory_tweet_id[] = $tweet_id;
				$destory_tweet_time[] = $tweettime2;
				$destory_tweet_count[] = $count;

				if(is_int($destory_time)){
					print("削除対象のID:".$tweet_id."\n");
					print("ツイート削除まで".$destory_time."時間\n");
				}

			}
			else{
				print("時間後に削除する対象ではありません\n");
			}
		}
		else{
			print("時間後に削除する対象ではありません\n");
		}
	}
	else{
		print("時間後に削除する対象ではありません\n");
	}
	//print_r($tweet->entities->hashtags);
	//print_r($tweet->created_at);

}

//削除対象のツイートIDと削除時間を一覧表示
print("\n削除対象のツイートIDと削除時間を表示\n");
print_r($destory_tweet_id);
print_r($destory_tweet_time);
print_r($destory_tweet_count);

//現在時刻取得
$nowtime = date("現在時間とタイムスタンプ\nH:i:s\n");
print($nowtime);

//現在時刻をタイムスタンプ化
$nowtime2 = time($nowtime);
print($nowtime2."\n");

//投稿時間と現在時刻の差分をタイムスタンプから求める(単位は秒)
//$time_different = ($nowtime2-$tweettime2);
//print("投稿からの経過時間:".$time_different."秒\n");

//投稿時間からの経過時間を計算
//$hour = floor($time_different/3600);
//$time_different -= ($hour*3600);
//$min = floor($time_different/60);
//$time_different -= ($min*60);
//$sec = $time_different;

//print("投稿からの経過時間:".$hour."h".$min."min".$sec."sec");

for($i = 0 ; $i < count($destory_tweet_count); $i++){

	//削除対象のツイートの投稿時間と現在時刻の差分を算出(単位は秒)
	$destory_time_different = $nowtime2 - $destory_tweet_time[$i];

	//差分時間からツイートの削除をするか判断
	if($destory_time_different > $destory_tweet_count[$i]){

		print("次の対象ツイートIDを削除:".$destory_tweet_id[$i]."\n");
		destorytweet($destory_tweet_id[$i],$connection,$username);

	}
}

//$res = $connection->post("statuses/destroy", array("status" => "test2"));

?>