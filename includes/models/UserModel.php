<?php

Class UserModel{
    /*
     * Modelo del usuario de gamificación 
     * */
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


    public static function addUser($user){
        $dbw = wfGetDB( DB_MASTER );
        $dbw->insert(
            'gamification',
            [
                'gam_user_id' => $user->getId(),
                'gam_user_text' => $user->getName()
            ]
        );
    }

    public static function getUserByName($userName){
        /*
         * Solo debe ser llamada con un usuario válido.
         */
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
    
    public static function incLogins($user){
        $id_user = $user->getId();
        $dbw = wfGetDB( DB_MASTER );
        $dbw->update(
            'gamification',
            ['gam_logins = gam_logins + 1'],
            ["gam_user_id = $id_user"]
        );
    }

    public static function incColaborations($user){
        $id_user = $user->getId();
        $dbw = wfGetDB( DB_MASTER );
        $dbw->update(
            'gamification',
            ['gam_number_of_colaboration = gam_number_of_colaboration + 1'],
            ["gam_user_id = $id_user"]
        );
    }

    public static function createdPage($user){
        $id_user = $user->getId();
        $dbw = wfGetDB( DB_MASTER );
        $dbw->update(
            'gamification',
            ['gam_first_page_created = true'],
            ["gam_user_id = $id_user"]
        );
    }

    public static function modifiedPage($user){
        $id_user = $user->getId();
        $dbw = wfGetDB( DB_MASTER );
        $dbw->update(
            'gamification',
            ['gam_first_page_modified = true'],
            ["gam_user_id = $id_user"]
        );
    }
 }

?>