<?php

/*

 * PHP AES <-> Clickteam AesFusion extension

 * Written by Wilhelm von Post / Squarelab Games

 * Uses AES.class.php by Cody Phillips / Phillips Data

 * contact: willevp [at] gmail.com

 */



// define key constant for 128 bit AES encryption (16 bytes/chars)

define('AES_KEY', '41Iy7QKM82qxdg1p');



// include AES class file (if not already done or using class autoloading)

require_once('AES.class.php');



/*

 * Fusion AES decrypt function 

 * 128 bits AES decryption with conversion from HEX

 * can decrypt data from AesFusion extension

 * NOTES:

 * Fusion converts data to HEX uppercase after encryption

 * Fusion pads data with ASCII code 004 (end transmission) for uncomplete bytes

 * PHP pads with 000 (NULL), must remove these chars before using decrypted text

 */

function fusion_aes_decrypt($data, $key = AES_KEY) {

// create aes object

    $aes = new AES($key);

// convert from HEX using PHP pack function

    $data = pack('H*', $data);

// decrypt string

    $dstring = $aes->decrypt($data);

// trim ASCII END (4) chars from string end

    $dstring = rtrim($dstring, chr(4));

// finish!

    $aes = NULL;

    return $dstring;
}

/*

 * Fusion AES encrypt function

 * 128 bits AES encryption with HEX uppercase output

 * decryptable by MMF AesFusion extension

 */

function fusion_aes_encrypt($data, $key = AES_KEY) {

// create aes object

    $aes = new AES($key);

// encrypt string

    $estring = $aes->encrypt($data);

// hex and uppercase

    $estring = strtoupper(bin2hex($estring));

// finish!

    $aes = NULL;

    return $estring;
}
