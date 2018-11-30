<?php 

/**
 * project
 */
class MessengerApi
{
	public function receiveMSG($entrys)
	{
		$msgs = [];
		foreach ($entrys as $entry) {
			foreach ($entry['messaging'] as $msg) {
				$msgs[] = new Message($msg);
				__log(new Message($msg),'receiveMSGS.txt');
			}
		}
		return $msgs;
	}
	public function sendMessage($receiver,$content)
	{
		$request = array(
			'messaging_type' => 'RESPONSE',
			'recipient' => array(
				'id' => $receiver
			),
			'message' =>  array(
				'text' => $content
			)
		);
		__log(json_encode($request),'temp.txt');
		$url = 'https://graph.facebook.com/v2.6/me/messages?'.http_build_query(array('access_token'=>TOKEN));
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => json_encode($request),
			CURLOPT_RETURNTRANSFER => true
		));
		$content = curl_exec($ch);
		__log($content,'curlResult.txt');
	}
}

/**
 * Message Format
 */
class Message
{
	public $sender = null;
	public $text = null;
	function __construct($msg)
	{
		$this->sender = $msg['sender']['id'];
		$this->text = $msg['message']['text'];
	}
}