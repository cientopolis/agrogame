<?php

/*
 * Modelo del usuario de gamificación 
 * */


 Class UserModel{

    public static function get_info($user){

        $dbr = wfGetDB( DB_REPLICA );

        $res = $dbr->select(
            'gamification',
            ['gam_created_pages', 'gam_modified'],
            "gam_id = " . $user->getId()
        )->current();

        return [
            'created_pages' => $res->gam_created_pages,
            'modified_pages' => $res->gam_modified
        ];
    }

    public static function get_progress($user){

        $dbr = wfGetDB( DB_REPLICA );

        $res = $dbr->select(
            'gamification_progress',
            ['gam_prog_created_page', 'gam_prog_modified_page'],
            ["gam_id = " . $user->getId()]
        )->current();

        return [
            'created_page' => $res->gam_prog_created_page, 
            'modified_page' => $res->gam_prog_modified_page
        ];
    }

 }

?>