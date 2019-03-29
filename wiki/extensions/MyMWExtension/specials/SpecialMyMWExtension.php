<?php
/**
 * myMWExtension SpecialPage for MyMWExtension extension
 *
 * @file
 * @ingroup Extensions
 */
class SpecialMyMWExtension extends SpecialPage {
	public function __construct() {
		parent::__construct( 'myMWExtension' );
	}

	/**
	 * Show the page to the user
	 *
	 * @param string $sub The subpage string argument (if any).
	 */
	public function execute( $sub ) {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'special-myMWExtension-title' ) );
		$out->addHelpLink( 'How to become a MediaWiki hacker' );
		$out->addWikiMsg( 'special-myMWExtension-intro' );
	}

	protected function getGroupName() {
		return 'other';
	}
}
