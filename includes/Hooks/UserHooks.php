<?php

class UserHooks{
    public static function onUserLoginComplete( User &$user, &$inject_html, $direct ) {
        /*
         * Aumenta la cantidad de loggins del usuario.
         * Si esta configurado el servidor aduino envía una request de login.
         */
        global $wgArduinoWebServerOn;
        UserModel::incLogins($user);
        if ($wgArduinoWebServerOn){
            $params = array('login' => 't');
            WebServer::get_request($params);
        }
    }
    public static function onLocalUserCreated( $user, $autocreated ) {
        /*
         * Crea el usuario en la DB.
         * Si hay servidor arduino configurado hace la respectiva request.
         */
        global $wgArduinoWebServerOn;
        UserModel::addUser($user);
        if ($wgArduinoWebServerOn){
            $params = array('created-user' => 't');
            WebServer::get_request($params);
        }
    }    
}