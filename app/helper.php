<?php

	function getConfig(){
		$config = require  '../../api/config/app.php';

		return $config;
	}

	function generateError($arr){
		foreach ($arr as $key => $value) {
	    	$message[] = $value[0];
	    }
	    
	    return implode(', ', $message);
	}

	function isJson($string) {
	 	json_decode($string);
	 	return (json_last_error() == JSON_ERROR_NONE);
	}

	function clientIp() {
	    $local = array('::1');
	    $ip = getenv('HTTP_CLIENT_IP')?:getenv('HTTP_X_FORWARDED_FOR')?:getenv('HTTP_X_FORWARDED')?:getenv('HTTP_FORWARDED_FOR')?:getenv('HTTP_FORWARDED')?:getenv('REMOTE_ADDR');
	    if( in_array($ip, $local) ){
	      $ip = '127.0.0.1';
	    }
	    return $ip;
	}

	function ecncrypt($plaintext, $key){
		$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
		$iv = openssl_random_pseudo_bytes($ivlen);

		$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
		$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

		return $ciphertext;
	}

	function decrypt($ciphertext, $key){
		$c = base64_decode($ciphertext);
		$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
		$iv = substr($c, 0, $ivlen);

		$hmac = substr($c, $ivlen, $sha2len=32);
		$ciphertext_raw = substr($c, $ivlen+$sha2len);
		$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
		$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
		if (check_hash_equals($hmac, $calcmac))
		{
		    return $original_plaintext;
		} else {
			return false;
		}
	}

	function check_hash_equals($str1, $str2) {
	    if(strlen($str1) != strlen($str2)) {
	      return false;
	    } else {
	      $res = $str1 ^ $str2;
	      $ret = 0;
	      for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
	      return !$ret;
	    }
	}