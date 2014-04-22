<?php

/*
 Apply tripleDES algorthim for encryption, append "___EOT" to encrypted file ,
 so that we can remove it while decrpytion also padding 0's
 */
 function TripleDesEncrypt($buffer,$key,$iv) {
 
$cipher = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
 $buffer.='___EOT';
 // get the amount of bytes to pad
 $extra = 8 - (strlen($buffer) % 8);
 // add the zero padding
 if($extra > 0) {
 for($i = 0; $i < $extra; $i++) {
 $buffer .= '_';
 }
 }
 mcrypt_generic_init($cipher, $key, $iv);
 $result = mcrypt_generic($cipher, $buffer);
 mcrypt_generic_deinit($cipher);
 return base64_encode($result);
 }
 
/*
 Apply tripleDES algorthim for decryption, remove "___EOT" from encrypted file ,
 so that we can get the real data.
 */
 function TripleDesDecrypt($buffer,$key,$iv) {
 $buffer= base64_decode($buffer);
 $cipher = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
 mcrypt_generic_init($cipher, $key, $iv);
 $result = mdecrypt_generic($cipher,$buffer);
 $result=substr($result,0,strpos($result,'___EOT'));
 mcrypt_generic_deinit($cipher);
 return $result;
 }



function Encrypt($source, $destination,$key) {

 $iv="password";
if (extension_loaded('mcrypt') === true)
 {
   if (is_file($source) === true)
     {
      $InFile = file_get_contents($source);
      unlink($source);
      $encryptedSource=TripleDesEncrypt($InFile,$key,$iv);
      if (file_put_contents($destination,$encryptedSource, LOCK_EX) !== false)
       {
        return true;
       }
     return false;
     }
 return false;
 }
return false;
 }
 
function Decrypt($source, $destination,$key) {
 $iv="password";
 if (extension_loaded('mcrypt') === true)
 {
 if (is_file($source) === true)
 {
 $InFile = file_get_contents($source);
 unlink($source);
 $decryptedSource=TripleDesDecrypt($InFile,$key,$iv);
 if (file_put_contents($destination,$decryptedSource, LOCK_EX) !== false)
 {
 return true;
 }
 echo "no read";
 return false;
 }
 echo "no file";
 return false;
 }
 echo "no mcrypt";
 
return false;
 }
 

 ?>