<?php

// OAuth���C�u�����̓ǂݍ���
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

date_default_timezone_set('Asia/Tokyo');

//�F�؏��S��
$consumerKey = "Zs9qIvoBQtSJXI8CwUvKmhrIP";
$consumerSecret = "dYx5dmZ50SjttRnzb1tTietbJhE7iF6WdHABSIv7DnDB7MGHt1";
$accessToken = "142140468-RgnpGzC2PXlOkF4hrnzrOpNBWI0Prvjm3kQ1IP9C";
$accessTokenSecret = "g6sETQqrDVhqZtbonrPg1IfwcsxuL6sTC8ZqQ0AFYaBDj";

//�ڑ�
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$username = "chanotopngn";

$timeline = $connection->get("statuses/user_timeline", array("screen_name" => $username,'count'=>'100'));

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
	$tweet_time = strtotime((string) $tweet->created_at);
	print("\n���e���ԁF".$tweet_time."\n");
	
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
		if( $hashtagtweet_unit == "m" || $hashtagtweet_unit == "h"){

			print("�n�b�V���^�O�̓��e�F".$hashtagtweet."\n");
			print("�n�b�V���^�O�̍Ō�̕�����m�ł�\n");
			print("�n�b�V���^�O�̍Ō�̕����ȊO�F".$hashtagtweet_time."\n");

			//�n�b�V���^�O�̍Ō�̕����ȊO�������ł��邩����
			if(ctype_digit($hashtagtweet_time)){

				print("���Ԍ�ɍ폜����Ώۂł�\n");

				//���ݎ����擾
				$nowtime = date("���ݎ��Ԃƃ^�C���X�^���v\nH:i:s\n");
				//print($nowtime);

				//���ݎ������^�C���X�^���v��
				$nowtime2 = time($nowtime);
				//print($nowtime2."\n");

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
				
				//�폜�Ώۂ̃c�C�[�gID�擾
				$tweet_id = $tweet->id;

				//�폜�Ώۂ̃c�C�[�g�̓��e���Ԃƌ��ݎ����̍������Z�o(�P�ʂ͕b)
				$destory_time_different = $nowtime2 - $tweet_time;

				//�������Ԃ���c�C�[�g�̍폜�����邩���f
				if($destory_time_different > $count){

					print("���̑Ώۃc�C�[�gID���폜:".$tweet_id."\n");
					destorytweet($tweet_id,$connection,$username);

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
if(!empty($destory_tweet_id)){

	print("\n�폜�Ώۂ̃c�C�[�gID�ƍ폜���Ԃ�\��\n");
	print_r($destory_tweet_id);
	print_r($destory_tweet_time);
	print_r($destory_tweet_count);

}


for($i = 0 ; $i < count($destory_tweet_count); $i++){

	//�폜�Ώۂ̃c�C�[�g�̓��e���Ԃƌ��ݎ����̍������Z�o(�P�ʂ͕b)
	$destory_time_different = $nowtime2 - $destory_tweet_time[$i];

	//�������Ԃ���c�C�[�g�̍폜�����邩���f
	if($destory_time_different > $destory_tweet_count[$i]){

		print("���̑Ώۃc�C�[�gID���폜:".$destory_tweet_id[$i]."\n");
		destorytweet($destory_tweet_id[$i],$connection,$username);

	}
}

?>