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
        if(curl_errno($ch) !== 0) {
            //error_log('cURL error when connecting to ' . $wgArduinoWebServerIP . ': ' . curl_error($ch));
            //GamLog::write('cURL error when connecting to ' . $wgArduinoWebServerIP . ': ' . curl_error($ch));
        }/*else{
            GamLog::write("correct GET sended to " . $wgArduinoWebServerIP  . '?' . $getParams);
            GamLog::write("La respuesta del GET fue: $result");
            GamLog::write("La variable wgArduinoWebServerIP es: $wgArduinoWebServerIP" );
        }*/
        curl_close($ch);

    }
}