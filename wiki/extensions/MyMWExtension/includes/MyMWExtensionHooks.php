<?php


class MyMWExtensionHooks {

    //Se ejecuta cuando se hace el update.php
    public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$updater->addExtensionTable( 'gamification',
		__DIR__ . "$base/../sql/patch-mymwextension.sql" );
	    return true;
    }
    
    
}