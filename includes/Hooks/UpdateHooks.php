<?php

Class UpdateHooks{
    public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
        /*
         * Además de crear lo necesario en la BD, Completa las tablas con usuarios que ya existen.
         */
        $updater->addExtensionTable( 'gamification', __DIR__ . "/../../sql/patch-mymwextension.sql" );
        $updater->addExtensionUpdate([ [ UserModel::class, 'addExistingUsers' ] ]);
    }
}