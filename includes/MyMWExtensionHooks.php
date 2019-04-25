<?php


class MyMWExtensionHooks {

    //Se ejecuta cuando se hace el update.php
    public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {

        GamLog::write("Creating gamification table...");
        $updater->addExtensionTable( 'gamification', __DIR__ . "/../sql/patch-mymwextension.sql" );
        GamLog::write("gamification table created.");
        GamLog::write("Adding existing users...");
        $updater->addExtensionUpdate([ [ __CLASS__, 'addExistingUsers' ] ]);
        GamLog::write("Existing users added.");
    }


    //Se ejecuta luego de crear la BD. Es una callback function llamada desde onLoadExtensionSchemaUpdates
    //No es Hook
    public static function addExistingUsers(DatabaseUpdater $updater){

        $dbw = wfGetDB( DB_MASTER );
        $dbw->startAtomic( __METHOD__ );

        $res = $dbw->select(
            'user',
            ['user_id', 'user_name'],
            '*'
        );

        foreach($res as $row){
            //Si el usuario fue agregado antes no lo inserta.
            if($dbw->select('gamification','gam_user_id',["gam_user_id = $row->user_id"])->numRows()>0) continue;

            $dbw->insert(
                'gamification',
                [
                    'gam_user_id' => $row->user_id,
                    'gam_user_text' => $row->user_name
                ]
            );

        }
        
        $dbw->endAtomic( __METHOD__ );
    }

    //Se ejecuta cuando un usuario se loguea correctamente
    public static function onUserLoginComplete( User &$user, &$inject_html, $direct ) {

        $id_user = $user->getId();

        $dbw = wfGetDB( DB_MASTER );
        $dbw->update(
            'gamification',
            ['gam_logins = gam_logins + 1'],
            ["gam_user_id = $id_user"]
        );

        GamLog::write("$user (id: ". $user->getId() . ") logueado.");

        $params = array(
            'LOGIN' => 'T'
        );

        WebServer::request($params);
    }

    public static function onLocalUserCreated( $user, $autocreated ) {

        GamLog::write('A new user was created, adding to database...');
        $dbw = wfGetDB( DB_MASTER );
        $dbw->insert(
            'gamification',
            [
                'gam_user_id' => $user->getId(),
                'gam_user_text' => $user->getName()
            ]
        );
        GamLog::write('New user added.');
    }

}