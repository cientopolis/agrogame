<?php


class OtherHooks { 
    /*
     * Hooks restantes
     */
    public static function onParserFirstCallInit( Parser $parser ) {
        /*
         * Inicializa la parse function.
         */
        $parser->setFunctionHook( 'infoGamUser', [ self::class, 'infoGamUser' ] );
    }

    public static function infoGamUser( Parser $parser, $userName = '') {
        /*
         * Definición de parse function.
         */
        wfDebugLog("mymwextension", "username: $userName");
        
        $parser->disableCache();
        $user = UserModel::getUserByName($userName);
        $porcentUser = 0.73;// acá se obtendría el porcentaje del user.
        $parser->getOutput()->addJsConfigVars("porcentUser", $porcentUser);
        $output = self::gamUserToHTML($user);
        return $output;
    }


    public static function gamUserToHTML($user){
        /*
         * Utilidad de la definición de la parse function
         */
        $li1 = ($user['gam_first_page_created'] ? "Si" : "No");
        $li2 = ($user['gam_first_page_modified'] ? "Si" : "No");
        $li3 = ($user['gam_logins']);

        return "
        <div class=flex-container>
        <ul>
            <li><strong>Creó al menos una página:</strong> $li1</li>
            <li><strong>Modificó al menos una página:</strong> $li2</li>
            <li><strong>Cantidad de veces que inició sesión:</strong> $li3</li>
        </ul>
        <div id=container></div>
        </div>
        ";
    }

    public static function onPageContentSaveComplete( $wikiPage, $user, $content, $summary, $isMinor, $isWatch, $section, &$flags, $revision, $status, $baseRevId, $undidRevId ) {
        $idrevdepag = $wikiPage->getTitle()->getFirstRevision()->getId() == $revision->getId();
        $idrevhecha = $revision->getId();

        ($wikiPage->getTitle()->getFirstRevision()->getId() == $revision->getId())?     UserModel::createdPage($user) : UserModel::modifiedPage($user);
        UserModel::incColaborations($user);
    }

    public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
        $out->addModules("ext.myMWExtension");    
    }

}