<?php

//Clase utilizada para implementar un log
class GamLog {
    public static function write($text){

        $path = __DIR__ . "/logb.log";

        if(!file_exists($path)){
            $file = fopen($path, "w");
            fclose($file);
            chmod($path, 0666);
        }

        file_put_contents( $path, "[" . date("m/d/Y H:i:s") . "]: " . $text . "\n", FILE_APPEND | LOCK_EX);
    }
}
