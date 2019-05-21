<?php

/*
 * Modelo del usuario de gamificación 
 * */


 Class UserModel{

    /*
     * Solo debe ser llamada con un usuario válido.
     */
    public static function getUserByName($userName){

        $dbr = wfGetDB( DB_REPLICA );

        $res = $dbr->select(
            'gamification',
            ['gam_first_page_created', 'gam_first_page_modified', 'gam_logins', 'gam_number_of_colaboration'],
            "gam_user_text = \"$userName\""
        )->current();

        return [
            'gam_first_page_created' => $res->gam_first_page_created,
            'gam_first_page_modified' => $res->gam_first_page_modified,
            'gam_logins' => $res->gam_logins,
            'gam_number_of_colaboration' => $res->gam_number_of_colaboration
        ];
    }

 }

?>