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

$destory_tweet_id = array();
$destory_tweet_time = array();
$destory_tweet_count = array();

//�c�C�[�g���폜����֐�
function destorytweet($id,$connection,$username){
	//print "statuses/destroy/{$id}";
	$connection->post("statuses/destroy/{$id}", array("screen_name" => $username));
	//post("statuses/destroy/{$id}", array("screen_name" => $username));
}

foreach($timeline as $tweet){

	//���e���Ԏ擾
	$tweettime = date("Y/m/d H:i:s", strtotime((string) $tweet->created_at));
	print("\n���e���ԁF".$tweettime."\n");

	//���e���Ԃ��^�C���X�^���v��
	$tweettime_Y = date("Y", strtotime((string) $tweet->created_at));
	$tweettime_m = date("m", strtotime((string) $tweet->created_at));
	$tweettime_d = date("d", strtotime((string) $tweet->created_at));
	$tweettime_H = date("H", strtotime((string) $tweet->created_at));
	$tweettime_i = date("i", strtotime((string) $tweet->created_at));
	$tweettime_s = date("s", strtotime((string) $tweet->created_at));
	$tweettime2 = mktime($tweettime_H,$tweettime_i,$tweettime_s,$tweettime_m,$tweettime_d,$tweettime_Y);
	//print("�^�C���X�^���v�F".$tweettime2."\n\n");
	
	//�n�b�V���^�O�̎擾
	//print_r($tweet->entities->hashtags[0]->text."\n");
	//print_r($tweet->entities->hashtags[0]);
	if(!empty($tweet->entities->hashtags[0]->text)){

		//�n�b�V���^�O�̎擾
		$hashtagtweet = $tweet->entities->hashtags[0]->text;

		//�n�b�V���^�O�̒������擾
		$hashtagtweet_long = strlen($hashtagtweet);

		//�n�b�V���^�O�̍Ō�̕������擾
		$hashtagtweet_unit = substr($hashtagtweet, -1,1);

		//�n�b�V���^�O�̍Ō�̕����ȊO���擾
		$hashtagtweet_time = substr($hashtagtweet, 0, $hashtagtweet_long - 1 );

		//�n�b�V���^�O�̍Ō�̕�����"m"��"h"�𔻒�
		if($hashtagtweet_unit == "m" || $hashtagtweet_unit == "��"){

			print("�n�b�V���^�O�̓��e�F".$hashtagtweet."\n");
			print("�n�b�V���^�O�̍Ō�̕�����m�ł�\n");
			print("�n�b�V���^�O�̍Ō�̕����ȊO�F".$hashtagtweet_time."\n");

			//�n�b�V���^�O�̍Ō�̕����ȊO�������ł��邩����
			if(ctype_digit($hashtagtweet_time)){

				print("���Ԍ�ɍ폜����Ώۂł�\n");

				//�n�b�V���^�O�̍Ō�̕����ȊO�𐮐��ɕϊ�
				$destory_time = intval($hashtagtweet_time);
				//������Ή����鐔�l�ɕϊ�
				$count = $destory_time * 60;

				//�폜�Ώۂ̃c�C�[�gID�擾
				$tweet_id = $tweet->id;

				//�폜�Ώۂ̃c�C�[�gID�ƍ폜���Ԃ�z��ɒǉ�
				$destory_tweet_id[] = $tweet_id;
				$destory_tweet_time[] = $tweettime2;
				$destory_tweet_count[] = $count;

				if(is_int($destory_time)){
					print("�폜�Ώۂ�ID:".$tweet_id."\n");
					print("�c�C�[�g�폜�܂�".$destory_time."��\n");
				}
			}
			else{
				print("���Ԍ�ɍ폜����Ώۂł͂���܂���\n");
			}
		}
		elseif($hashtagtweet_unit == "h" || $hashtagtweet_unit == "��"){

			print("�n�b�V���^�O�̓��e�F".$hashtagtweet."\n");
			print("�n�b�V���^�O�̍Ō�̕�����h�ł�\n");
			print("�n�b�V���^�O�̍Ō�̕����ȊO�F".$hashtagtweet_time."\n");

			//�n�b�V���^�O�̍Ō�̕����ȊO�������ł��邩����

			if(ctype_digit($hashtagtweet_time)){

				print("���Ԍ�ɍ폜����Ώۂł�\n");

				//�n�b�V���^�O�̍Ō�̕����ȊO�𐮐��ɕϊ�
				$destory_time = intval($hashtagtweet_time);
				//������Ή����鐔�l�ɕϊ�
				$count = $destory_time * 3600;

				//�폜�Ώۂ̃c�C�[�gID�擾
				$tweet_id = $tweet->id;

				//�폜�Ώۂ̃c�C�[�gID�ƍ폜���Ԃ�z��ɒǉ�
				$destory_tweet_id[] = $tweet_id;
				$destory_tweet_time[] = $tweettime2;
				$destory_tweet_count[] = $count;

				if(is_int($destory_time)){
					print("�폜�Ώۂ�ID:".$tweet_id."\n");
					print("�c�C�[�g�폜�܂�".$destory_time."����\n");
				}

			}
			else{
				print("���Ԍ�ɍ폜����Ώۂł͂���܂���\n");
			}
		}
		else{
			print("���Ԍ�ɍ폜����Ώۂł͂���܂���\n");
		}
	}
	else{
		print("���Ԍ�ɍ폜����Ώۂł͂���܂���\n");
	}
	//print_r($tweet->entities->hashtags);
	//print_r($tweet->created_at);

}

//�폜�Ώۂ̃c�C�[�gID�ƍ폜���Ԃ��ꗗ�\��
print("\n�폜�Ώۂ̃c�C�[�gID�ƍ폜���Ԃ�\��\n");
print_r($destory_tweet_id);
print_r($destory_tweet_time);
print_r($destory_tweet_count);

//���ݎ����擾
$nowtime = date("���ݎ��Ԃƃ^�C���X�^���v\nH:i:s\n");
print($nowtime);

//���ݎ������^�C���X�^���v��
$nowtime2 = time($nowtime);
print($nowtime2."\n");

//���e���Ԃƌ��ݎ����̍������^�C���X�^���v���狁�߂�(�P�ʂ͕b)
//$time_different = ($nowtime2-$tweettime2);
//print("���e����̌o�ߎ���:".$time_different."�b\n");

//���e���Ԃ���̌o�ߎ��Ԃ��v�Z
//$hour = floor($time_different/3600);
//$time_different -= ($hour*3600);
//$min = floor($time_different/60);
//$time_different -= ($min*60);
//$sec = $time_different;

//print("���e����̌o�ߎ���:".$hour."h".$min."min".$sec."sec");

for($i = 0 ; $i < count($destory_tweet_count); $i++){

	//�폜�Ώۂ̃c�C�[�g�̓��e���Ԃƌ��ݎ����̍������Z�o(�P�ʂ͕b)
	$destory_time_different = $nowtime2 - $destory_tweet_time[$i];

	//�������Ԃ���c�C�[�g�̍폜�����邩���f
	if($destory_time_different > $destory_tweet_count[$i]){

		print("���̑Ώۃc�C�[�gID���폜:".$destory_tweet_id[$i]."\n");
		destorytweet($destory_tweet_id[$i],$connection,$username);

	}
}

//$res = $connection->post("statuses/destroy", array("status" => "test2"));

?>