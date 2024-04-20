<?php

define("ENCRYPTION_KEY", "COPEL0978564312.");
define("secret_iv", ".");

function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

function decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}

class Cipher {
    private $securekey, $iv;
    function __construct($textkey) {
        $this->securekey = hash('sha256',$textkey,TRUE);
        $this->iv = mcrypt_create_iv(32);
    }
    function encrypt($input) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
    }
    function decrypt($input) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
    }
}

function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = ENCRYPTION_KEY;
    $secret_iv = secret_iv;

    // hash
    $key = hash('sha256', $secret_key);
    $iv = hash('sha256', $secret_iv);
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, $iv);
        $output = base64_encode($output);
        $output = urlencode($output);
		//$output = base64_encode (convert_uuencode ($string));
    }
    else if( $action == 'decrypt' ){
        $output = $decryptedMessage = openssl_decrypt(base64_decode($string), $encrypt_method, $key, $iv);
		 //$output = convert_uudecode (base64_decode ($string));
    }

    return $output;
}

?>