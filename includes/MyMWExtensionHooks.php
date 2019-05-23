<?php


class MyMWExtensionHooks {

    /*
     * Además de crear lo necesario en la BD, Completa las tablas con usuarios que ya existen.
     */
    public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {

        $updater->addExtensionTable( 'gamification', __DIR__ . "/../sql/patch-mymwextension.sql" );
        $updater->addExtensionUpdate([ [ __CLASS__, 'addExistingUsers' ] ]);

    }

    /*
     * Completa las tablas con usuarios que ya existen
     */
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

    /*
     * Aumenta la cantidad de loggins del usuario.
     * Si esta configurado el servidor aduino envía una request de login.
     */
    public static function onUserLoginComplete( User &$user, &$inject_html, $direct ) {

        global $wgArduinoWebServerOn;

        $id_user = $user->getId();

        $dbw = wfGetDB( DB_MASTER );
        $dbw->update(
            'gamification',
            ['gam_logins = gam_logins + 1'],
            ["gam_user_id = $id_user"]
        );

        if ($wgArduinoWebServerOn){
            $params = array('login' => 't');
            WebServer::get_request($params);
        }

    }

    /*
     * Crea el usuario en la DB.
     * Si hay servidor arduino configurado hace la respectiva request.
     */
    public static function onLocalUserCreated( $user, $autocreated ) {

        global $wgArduinoWebServerOn;

        $dbw = wfGetDB( DB_MASTER );
        $dbw->insert(
            'gamification',
            [
                'gam_user_id' => $user->getId(),
                'gam_user_text' => $user->getName()
            ]
        );

        if ($wgArduinoWebServerOn){
            $params = array('created-user' => 't');
            WebServer::get_request($params);
        }


    }     

     public static function onParserFirstCallInit( Parser $parser ) {
		
        $parser->setFunctionHook( 'infoGamUser', [ self::class, 'infoGamUser' ] );

     }

     public static function infoGamUser( Parser $parser, $userName = '') {

        MWDebug::log('Que onda esto');
        MWDebug::log('Y esto...');
        $parser->disableCache();
        $user = UserModel::getUserByName($userName);
        $output = self::gamUserToHTML($user);
        return $output;
     }


     public static function gamUserToHTML($user){

        return 
        "
        Creó al menos una página: " . ($user['gam_first_page_created'] ? "Si" : "No") . " \n
        Modificó al menos una página: " . ($user['gam_first_page_modified'] ? "Si" : "No") . "\n
        Cantidad de veces que inició sesión: " . ($user['gam_logins']);
     }

     public static function onPageContentSaveComplete( $wikiPage, $user, $content, $summary, $isMinor, $isWatch, $section, &$flags, $revision, $status, $baseRevId, $undidRevId ) {
         
        $id_user = $user->getId();

        $dbw = wfGetDB( DB_MASTER );
        $dbw->update(
            'gamification',
            ['gam_number_of_colaboration = gam_number_of_colaboration + 1'],
            ["gam_user_id = $id_user"]
        );
      }

}