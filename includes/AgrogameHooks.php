<?php

class AgrogameHooks {

    public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModules( 'ext.agrogame' );
	}

}