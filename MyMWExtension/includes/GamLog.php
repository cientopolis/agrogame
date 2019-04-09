<?php

//Clase utilizada para implementar un log
class GamLog {
    public static function write($text){
        file_put_contents( __DIR__ . "/logb.log", "[" . date("m/d/Y H:i:s") . "]: " . $text . "\n", FILE_APPEND | LOCK_EX);
        if(!file_exists(__DIR__ . "/logb.log")) chmod(__DIR__ . "/logb.log", 0666);
    }
}
