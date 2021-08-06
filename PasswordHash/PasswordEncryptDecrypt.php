<?php
class PasswordEncryptDecrypt{
    public function PasswordEncrypt($password){
        $password_hash="123";
        $password_hash.=$password;
        return $password_hash;
    }
    public function PasswordDecrypt($passwordhash){
        return substr($passwordhash,3);
    }
}