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
        $parser->disableCache();
        $user = UserModel::getUserByName($userName);
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
        <ul>
            <li><strong>Creó al menos una página:</strong> $li1</li>
            <li><strong>Modificó al menos una página:</strong> $li2</li>
            <li><strong>Cantidad de veces que inició sesión:</strong> $li3</li>
        </ul>";
    }

    public static function onPageContentSaveComplete( $wikiPage, $user, $content, $summary, $isMinor, $isWatch, $section, &$flags, $revision, $status, $baseRevId, $undidRevId ) {
        ($wikiPage->getTitle()->getFirstRevision()->getId() == $revision->getId())?     UserModel::createdPage($user) : UserModel::modifiedPage($user);
        UserModel::incColaborations($user);
    }

}