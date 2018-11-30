<?php

require_once 'lib.php';
require_once 'MessengerApi.php';


$token = file_get_contents('facebook_token.txt');
define('TOKEN', $token);

$query = $_SERVER['QUERY_STRING'];
parse_str($query,$output);
// webhook
$mode = $output['hub_mode'] ?? null;
$challenge = $output['hub_challenge'] ?? null;
$verify_token = $output['hub_verify_token'] ?? null;

if ($mode && $verify_token) {
	if ($mode === 'subscribe' && $verify_token === $token ) {
		header("HTTP/1.1 200 OK");
		header('Content-Type: text/plain');
		echo $challenge;
	}
}

// receive messages

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if ($input['object'] === 'page') {
	header('HTTP/1.1 200 OK');
	$api = new MessengerApi();
	$msgs = $api->receiveMSG($input['entry']);
	foreach ($msgs as $msg) {
		$api->sendMessage($msg->sender,$msg->text);
	}
}