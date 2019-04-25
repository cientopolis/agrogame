<?php


//Clase generadora de request para el servidor web
class WebServer {

    private static $url = "192.168.10.55/";

    public static function request($params){

        $getParams = "";
        foreach ($params as $key => $value) {
            $getParams .= $key . "=" . $value . "&";
        }

        $getParams = trim($getParams, '&');

        $ch = curl_init(self::$url);

        curl_setopt($ch, CURLOPT_URL, self::$url . '?' . $getParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($ch);
        if(curl_errno($ch) !== 0) {
            error_log('cURL error when connecting to ' . self::$url . ': ' . curl_error($ch));
            GamLog::write('cURL error when connecting to ' . self::$url . ': ' . curl_error($ch));
        }else{
            GamLog::write("correct GET sended to " . self::$url . '?' . $getParams);
            GamLog::write("La respuesta del GET fue: $result");
            GamLog::write("La variable wgArduinoWebServerOn es: " . $wgArduinoWebServerOn);
        }
        curl_close($ch);

    }
}