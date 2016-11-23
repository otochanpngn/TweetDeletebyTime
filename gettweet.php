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

//DBにアクセス
$dsn = "mysql:dbname=destoryed_tweet;host=localhost";
$dbusername = "root";
$dbpassward = "pngnotochan1221";

//接続
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$username = "chanotopngn";

try{
	$dbh = new PDO($dsn, $dbusername, $dbpassward);
	print("DBにアクセス成功\n");
	//$dbh->query('SET NAMES sjis');
	$sql = "select * from user_list";

}catch(PDOException $e){
	print("Error:".$e->getMessage());
	die();
}

$user_list_username = "select * from user_list whre username=$username";
print($user_list_username."\n");

$timeline = $connection->get("statuses/user_timeline", array("screen_name" => $username,'count'=>'100'));

$forcount = 0;

foreach($timeline as $tweet){

	$forcount += 1;
	
	//ハッシュタグの取得
	//print_r($tweet->entities->hashtags[0]->text."\n");
	//print_r($tweet->entities->hashtags[0]);
	if(!empty($tweet->entities->hashtags[0]->text)){

		//全ハッシュタグを取得
		for($i = 0 ; $i < count($tweet->entities->hashtags); $i++){

			//ハッシュタグの取得
			$hashtagtweet = $tweet->entities->hashtags[$i]->text;

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

					//投稿時間取得
					$tweet_time = strtotime((string) $tweet->created_at);
					print("投稿時間：".$tweet_time."\n");
					//print($tweet->text);

					//削除対象のツイートID取得
					$tweet_id = $tweet->id;
					print("ID:".$tweet_id."\n");

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

					//データベースにアクセス
					try{
						$dbh = new PDO($dsn, $dbusername, $dbpassward);
						print("DBにアクセス成功\n");
						//$dbh->query('SET NAMES sjis');
						$sql = "select * from tweet_info";
						print("追加前のデータ一覧\n");
						foreach ($dbh->query($sql) as $row) {
        					//	print("ツイート時間：".$row["tweet_time"]."sec\n");
        					//	print("ツイート削除時間：".$row["count"]."sec\n");
        					//	print("ツイート時間：".$row["tweet_id"]."\n");
        					//	print("ツイート時間：".$row["username"]."\n");
						}
		
					}catch(PDOException $e){
						print("Error:".$e->getMessage());
						die();
					}

					//SQLにデータを追加
					$sql = "insert into tweet_info (tweet_time, count, tweet_id, username) values (?, ?, ?, ?)";
					$stmt = $dbh->prepare($sql);
					$flag = $stmt->execute(array($tweet_time, $count, $tweet_id, $username));
					//$stmt->bindParam(":id", $id);
					//$stmt->bindParam(":tweet_time", $tweet_time);
					//$stmt->bindParam(":count", $count);
					//$stmt->bindParam(":destory_time", $destory_time_different);
					//$stmt->bindparam(":tweet_id", $tweet_id);
					//$stmt->bindParam(":username", $username);

					if($flag){
						print("データの追加に成功\n");
					}
					else{
						print("データの追加に失敗\n");
					}

					print("追加後のデータ一覧\n");
					$sql = "select * from tweet_info";
					foreach ($dbh->query($sql) as $row) {
        				//	print($row["tweet_time"]."\n");
        				//	print($row["count"]."\n");
        				//	print($row["tweet_id"]."\n");
        				//	print($row["username"]."\n");
					}

					$dbh = null;

				}
				else{
					print("時間後に削除する対象ではありません\n");
				}
			}
			else{
				print("時間後に削除する対象ではありません\n");
			}
		}
	}

	else{
		print("時間後に削除する対象ではありません\n");
	}

	print("\n");
	//print_r($tweet->entities->hashtags);
	//print_r($tweet->created_at);

}

print($forcount);

?>