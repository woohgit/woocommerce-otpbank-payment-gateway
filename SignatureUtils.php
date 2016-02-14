<?php

class SignatureUtils {

    public static function loadPrivateKey($key_original) {
          $key = openssl_get_privatekey(str_replace("\r\n", "\n", $key_original));
          return $key;
    }

    /**
    * Aláírandó szöveg elõállítása az aláírandó szöveg értékek listájából:
    * [s1, s2, s3, s4]  ->  's1|s2|s3|s4'
    *
    * @param array aláírandó mezõk
    * @return string aláírandó szöveg
    */
    public static function getSignatureText($signatureFields) {
        $signatureText = '';
        foreach ($signatureFields as $data) {
            $signatureText = $signatureText.$data.'|';
        }

        if (strlen($signatureText) > 0) {
            $signatureText = substr($signatureText, 0, strlen($signatureText) - 1);
        }

        return $signatureText;
    }

    /**
    * Digitális aláírás generálása a Bank által elvárt formában.
    * Az aláírás során az MD5 hash algoritmust használjuk 5.4.8-nál kisebb verziójú PHP
    * esetén, egyébként SHA-512 algoritmust.
    *
    * @param string $data az aláírandó szöveg
    * @param resource $pkcs8PrivateKey privát kulcs
    *
    * @return string digitális aláírás, hexadecimális formában (ahogy a banki felület elvárja).
    */
    public static function generateSignature($data, $pkcs8PrivateKey) {

    	global $signature;

    	if (version_compare(PHP_VERSION, '5.4.8', '>=')) {
        	openssl_sign($data, $signature, $pkcs8PrivateKey, OPENSSL_ALGO_SHA512);
    	}
    	else {
    		openssl_sign($data, $signature, $pkcs8PrivateKey, OPENSSL_ALGO_MD5);
    	}

        return bin2hex($signature);
    }

}

?>
