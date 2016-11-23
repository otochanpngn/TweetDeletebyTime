<?php

// OAuthライブラリの読み込み
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

date_default_timezone_set('Asia/Tokyo');

//認証情報４つ
$consumerKey = "Zs9qIvoBQtSJXI8CwUvKmhrIP";
$consumerSecret = "dYx5dmZ50SjttRnzb1tTietbJhE7iF6WdHABSIv7DnDB7MGHt1";
$accessToken = "142140468-RgnpGzC2PXlOkF4hrnzrOpNBWI0Prvjm3kQ1IP9C";
$accessTokenSecret = "g6sETQqrDVhqZtbonrPg1IfwcsxuL6sTC8ZqQ0AFYaBDj";

//接続
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$username = "chanotopngn";

$timeline = $connection->get("statuses/user_timeline", array("screen_name" => $username,'count'=>'100'));

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
	$tweet_time = strtotime((string) $tweet->created_at);
	print("\n投稿時間：".$tweet_time."\n");
	
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
		if( $hashtagtweet_unit == "m" || $hashtagtweet_unit == "h"){

			print("ハッシュタグの内容：".$hashtagtweet."\n");
			print("ハッシュタグの最後の文字はmです\n");
			print("ハッシュタグの最後の文字以外：".$hashtagtweet_time."\n");

			//ハッシュタグの最後の文字以外が数字であるか判別
			if(ctype_digit($hashtagtweet_time)){

				print("時間後に削除する対象です\n");

				//現在時刻取得
				$nowtime = date("現在時間とタイムスタンプ\nH:i:s\n");
				//print($nowtime);

				//現在時刻をタイムスタンプ化
				$nowtime2 = time($nowtime);
				//print($nowtime2."\n");

				//ハッシュタグの最後の文字以外を整数に変換
				$destory_time = intval($hashtagtweet_time);

				//整数を対応する数値に変換
				if( $hashtagtweet_unit == "m"){

					$count = $destory_time * 60;
					//print("ツイート削除まで".$destory_time."分\n");

				}
				else{

					$count = $destory_time * 3600;
					//print("ツイート削除まで".$destory_time."時間\n");

				}
				
				//削除対象のツイートID取得
				$tweet_id = $tweet->id;

				//削除対象のツイートの投稿時間と現在時刻の差分を算出(単位は秒)
				$destory_time_different = $nowtime2 - $tweet_time;

				//差分時間からツイートの削除をするか判断
				if($destory_time_different > $count){

					print("次の対象ツイートIDを削除:".$tweet_id."\n");
					destorytweet($tweet_id,$connection,$username);

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
if(!empty($destory_tweet_id)){

	print("\n削除対象のツイートIDと削除時間を表示\n");
	print_r($destory_tweet_id);
	print_r($destory_tweet_time);
	print_r($destory_tweet_count);

}


for($i = 0 ; $i < count($destory_tweet_count); $i++){

	//削除対象のツイートの投稿時間と現在時刻の差分を算出(単位は秒)
	$destory_time_different = $nowtime2 - $destory_tweet_time[$i];

	//差分時間からツイートの削除をするか判断
	if($destory_time_different > $destory_tweet_count[$i]){

		print("次の対象ツイートIDを削除:".$destory_tweet_id[$i]."\n");
		destorytweet($destory_tweet_id[$i],$connection,$username);

	}
}

?>