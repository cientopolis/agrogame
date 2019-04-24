<?php


//Clase generadora de request para el servidor web
class WebServer {

    private static $url = "127.0.0.1/prueba/prueba.php";

    public static function Post($params){

        $JSONParams = json_encode($params);

        $ch = curl_init(self::$url);
        curl_setopt($ch, CURLOPT_URL, self::$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($ch);
        if(curl_errno($ch) !== 0) {
            error_log('cURL error when connecting to ' . self::$url . ': ' . curl_error($ch));
            GamLog::write('cURL error when connecting to ' . self::$url . ': ' . curl_error($ch));
        }else{
            GamLog::write("correct POST sended to " . self::$url);
            GamLog::write("La respuesta del POST fue: $result");
        }
        curl_close($ch);

    }
}