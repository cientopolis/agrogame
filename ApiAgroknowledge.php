<?php

/**
 * API for getting the page rating and voting for pages
 *
 * @file
 * @ingroup Extensions
 * @license MIT
 */
class ApiAgroknowledge extends ApiBase {
	public function execute() {
		global $wgRPRatingMin, $wgRPRatingMax;

		/*$params = $this->extractRequestParams();
		$this->requireOnlyOneParameter( $params, 'pageid', 'pagetitle' );

		if ( isset( $params['pageid'] ) ) {
			$title = Title::newFromID( $params['pageid'] );
		} else {
			$title = Title::newFromText( $params['pagetitle'] );
		}

		if ( is_null( $title ) || $title->getArticleID() < 1 ) {
			$this->dieWithError( 'Specified page does not exist' );
		}*/

		
		/*$this->getResult()->addValue( null, "pageId", $title->getArticleID() );
		
		if ( !RatePageRating::canPageBeRated( $title ) ) {
			return;
		}
		
		$user = RequestContext::getMain()->getUser();
		$ip = RequestContext::getMain()->getRequest()->getIP();
		if ( $user->getName() == '' ) {
			$userName = $ip;
		} else {
			$userName = $user->getName();
		}
		
		if ( isset( $params['answer'] ) ) {
			if ( $user->pingLimiter( 'ratepage' ) ) {
				$this->dieWithError( 'Rate limit for voting exceeded, please try again later' );
			}
			
			$answer = $params['answer'];
			if ( $answer < $wgRPRatingMin || $answer > $wgRPRatingMax ) {
				$this->dieWithError( 'Incorrect answer specified' );
			}
			RatePageRating::voteOnPage( $title, $userName, $ip, $answer );
		}
		
		$userVote = RatePageRating::getUserVote( $title, $userName, $ip );
		$pageRating = RatePageRating::getPageRating( $title );
		
		$this->getResult()->addValue( null, "pageRating", $pageRating );
		$this->getResult()->addValue( null, "userVote", $userVote );*/

		$dbr = wfGetDB( DB_REPLICA );

		$res = $dbr->select( 'ratepage_vote', [ 'rv_page_id', 'rv_answer' ], [], __METHOD__, [] );

		foreach( $res as $row ) {
			$this->getResult()->addValue( null, $row->rv_page_id, $row->rv_answer);
		}

	}
}
