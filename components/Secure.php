<?php

namespace app\components;


use app\models\User;

class Secure
{
	public static function Pack($data)
	{
		echo json_encode($data);
		exit;
		echo msgpack_pack($data);
		exit;
	}
	
	/* @var User $user */
	public static function Authorize($data, $key)
	{
		$mcrypt = new MCrypt();
		
		/* Encrypt */
		
		$data = explode('*', $data);
		if (!isset($data[1])) {
			Result::r403();
		}
		
		$decrypted = $data[0];
		
		$encrypted = $mcrypt->decrypt($data[1], $key);
		if ($encrypted != $decrypted) {
			Result::r403();
		}
	}
}