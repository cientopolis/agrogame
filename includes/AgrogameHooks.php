<?php

class AgrogameHooks {

	//Carga js,css en cada reload
    public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModules( 'ext.agrogame' );
	}

	public static function onLoadExtensionSchemaUpdates( $updater ) {
		$patchPath = __DIR__ . '/../sql/';

		$updater->addExtensionTable( 'ag_last_events', $patchPath . 'create-table--patch-agrogame.sql' );
	}

	public static function onUserLoginComplete( User &$user, &$inject_html, $direct ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );
		$dbw->insert( 'ag_last_events', [ 'ag_event' => 'login', 'ag_timestamp' => gmdate("Y-m-d.H:i:s")], __METHOD__ );
		$dbw->endAtomic( __METHOD__ );
	}

	public static function onPageContentSaveComplete( $wikiPage, $user, $mainContent, $summaryText, $isMinor, $isWatch, $section, &$flags, $revision, $status, $originalRevId, $undidRevId ){
		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );
		$dbw->insert( 'ag_last_events', [ 'ag_event' => 'save_page', 'ag_timestamp' => gmdate("Y-m-d.H:i:s")], __METHOD__ );
		$dbw->endAtomic( __METHOD__ );
	}

	public static function onLocalUserCreated( $user, $autocreated ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );
		$dbw->insert( 'ag_last_events', [ 'ag_event' => 'create_user', 'ag_timestamp' => gmdate("Y-m-d.H:i:s")], __METHOD__ );
		$dbw->endAtomic( __METHOD__ );
	}

}