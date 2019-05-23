<?php


//Clase generadora de request para el servidor web, recibe un arreglo con los parámetros que enviará vía get.
class WebServer {

    public static function get_request($params){

        global $wgArduinoWebServerIP;

        $getParams = "";
        foreach ($params as $key => $value) {
            $getParams .= $key . "=" . $value . "&";
        }

        $getParams = trim($getParams, '&');

        $ch = curl_init($wgArduinoWebServerIP);

        curl_setopt($ch, CURLOPT_URL, $wgArduinoWebServerIP . '?' . $getParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($ch);
        if(curl_errno($ch) !== 0);
        curl_close($ch);

    }
}