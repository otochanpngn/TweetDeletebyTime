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

//DB�ɃA�N�Z�X
$dsn = "mysql:dbname=destoryed_tweet;host=localhost";
$dbusername = "root";
$dbpassward = "pngnotochan1221";

//�ڑ�
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$username = "chanotopngn";

try{
	$dbh = new PDO($dsn, $dbusername, $dbpassward);
	print("DB�ɃA�N�Z�X����\n");
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
	
	//�n�b�V���^�O�̎擾
	//print_r($tweet->entities->hashtags[0]->text."\n");
	//print_r($tweet->entities->hashtags[0]);
	if(!empty($tweet->entities->hashtags[0]->text)){

		//�S�n�b�V���^�O���擾
		for($i = 0 ; $i < count($tweet->entities->hashtags); $i++){

			//�n�b�V���^�O�̎擾
			$hashtagtweet = $tweet->entities->hashtags[$i]->text;

			//�n�b�V���^�O�̒������擾
			$hashtagtweet_long = strlen($hashtagtweet);

			//�n�b�V���^�O�̍Ō�̕������擾
			$hashtagtweet_unit = substr($hashtagtweet, -1,1);

			//�n�b�V���^�O�̍Ō�̕����ȊO���擾
			$hashtagtweet_time = substr($hashtagtweet, 0, $hashtagtweet_long - 1 );

			//�n�b�V���^�O�̍Ō�̕�����"m"��"h"�𔻒�
			if( $hashtagtweet_unit == "m" || $hashtagtweet_unit == "h"){

				print("�n�b�V���^�O�̓��e�F".$hashtagtweet."\n");
				print("�n�b�V���^�O�̍Ō�̕�����m�ł�\n");
				print("�n�b�V���^�O�̍Ō�̕����ȊO�F".$hashtagtweet_time."\n");

				//�n�b�V���^�O�̍Ō�̕����ȊO�������ł��邩����
				if(ctype_digit($hashtagtweet_time)){

					print("���Ԍ�ɍ폜����Ώۂł�\n");

					//���e���Ԏ擾
					$tweet_time = strtotime((string) $tweet->created_at);
					print("���e���ԁF".$tweet_time."\n");
					//print($tweet->text);

					//�폜�Ώۂ̃c�C�[�gID�擾
					$tweet_id = $tweet->id;
					print("ID:".$tweet_id."\n");

					//�n�b�V���^�O�̍Ō�̕����ȊO�𐮐��ɕϊ�
					$destory_time = intval($hashtagtweet_time);

					//������Ή����鐔�l�ɕϊ�
					if( $hashtagtweet_unit == "m"){

						$count = $destory_time * 60;
						//print("�c�C�[�g�폜�܂�".$destory_time."��\n");

					}
					else{

						$count = $destory_time * 3600;
						//print("�c�C�[�g�폜�܂�".$destory_time."����\n");

					}

					//�f�[�^�x�[�X�ɃA�N�Z�X
					try{
						$dbh = new PDO($dsn, $dbusername, $dbpassward);
						print("DB�ɃA�N�Z�X����\n");
						//$dbh->query('SET NAMES sjis');
						$sql = "select * from tweet_info";
						print("�ǉ��O�̃f�[�^�ꗗ\n");
						foreach ($dbh->query($sql) as $row) {
        					//	print("�c�C�[�g���ԁF".$row["tweet_time"]."sec\n");
        					//	print("�c�C�[�g�폜���ԁF".$row["count"]."sec\n");
        					//	print("�c�C�[�g���ԁF".$row["tweet_id"]."\n");
        					//	print("�c�C�[�g���ԁF".$row["username"]."\n");
						}
		
					}catch(PDOException $e){
						print("Error:".$e->getMessage());
						die();
					}

					//SQL�Ƀf�[�^��ǉ�
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
						print("�f�[�^�̒ǉ��ɐ���\n");
					}
					else{
						print("�f�[�^�̒ǉ��Ɏ��s\n");
					}

					print("�ǉ���̃f�[�^�ꗗ\n");
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
					print("���Ԍ�ɍ폜����Ώۂł͂���܂���\n");
				}
			}
			else{
				print("���Ԍ�ɍ폜����Ώۂł͂���܂���\n");
			}
		}
	}

	else{
		print("���Ԍ�ɍ폜����Ώۂł͂���܂���\n");
	}

	print("\n");
	//print_r($tweet->entities->hashtags);
	//print_r($tweet->created_at);

}

print($forcount);

?>