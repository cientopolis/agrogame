<?php
/**
 * agrogame SpecialPage for agrogame extension
 *
 * @file
 * @ingroup Extensions
 */
class SpecialAgrogame extends SpecialPage {
	public function __construct() {
		parent::__construct( 'agrogame' );
	}

	/**
	 * Show the page to the user
	 *
	 * @param string $sub The subpage string argument (if any).
	 */
	public function execute( $sub ) {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'special-agrogame-title' ) );
		$out->addHelpLink( 'How to become a MediaWiki hacker' );
		$out->addWikiMsg( 'special-agrogame-intro' );
	}

	protected function getGroupName() {
		return 'other';
	}
}
