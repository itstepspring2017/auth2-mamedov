<?php

namespace ModuleHash;

class PassHasher{
    private $alg;
    private $salt_pos;
    private $salt_len;

    public function __construct(string $alg,int $salt_pos,int $salt_len){
        $this->alg = $alg;
        $this->salt_len = $salt_len;
        $this->salt_pos = $salt_pos;
    }

    private function createSalf():string {
        $first = md5(time());
        $second = md5(rand(0,999999));
        $salt = hash($this->alg,$first.$second);
        return substr($salt,0,$this->salt_len);
    }

    private function hashWithSalt(string $data,string $salt):string {
        $s1 = hash($this->alg,$data.$salt.substr($data,3));
        $s2 = hash($this->alg,$salt.$data.substr($salt,1));
        $hash = hash($this->alg,$s1.$s2);
        return substr_replace($hash,$salt,$this->salt_pos,$this->salt_len);
    }

    private function saltFromHash(string $hash){
        return substr($hash,$this->salt_pos,$this->salt_len);
    }

    public function passHash(string $pass):string {
        return $this->hashWithSalt($pass,$this->createSalf());
    }

    public function comparePass(string $pass,string $hash):bool {
        $salt = $this->saltFromHash($hash);
        $hash2 = $this->hashWithSalt($pass,$salt);
        return $hash2 === $hash;
    }
}