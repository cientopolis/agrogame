<?php

class ApiAgroknowledge extends ApiBase {
	public function execute() {

		$dbr = wfGetDB( DB_REPLICA );
		$res3 = $dbr->query('SELECT p.page_id, COALESCE(rv4.rv_answer, -1) as last_answer, COALESCE(rv4.avg_stars,-1) as avg_stars
		FROM `page` as p
		LEFT JOIN (
			 SELECT rv3.rv_page_id, rv3.rv_answer, rv2.avg_stars
			 FROM ratepage_vote as rv3
			 INNER JOIN (
				 SELECT rv.rv_page_id, MAX(rv.rv_date) as min_date, AVG(rv.rv_answer) as avg_stars
				 FROM `ratepage_vote`as rv
				 GROUP BY rv.rv_page_id
				 ) rv2 ON rv3.rv_page_id = rv2.rv_page_id AND rv3.rv_date = rv2.min_date
				) as rv4 ON p.page_id = rv4.rv_page_id
		WHERE p.page_namespace = 0
		ORDER BY p.page_id DESC
		', __METHOD__, 0);

		$i = 0;
		$page = [];
		foreach( $res3 as $row ) {
			$page["id"] = $row->page_id;
			$page["last_answer"] = $row->last_answer;
			$page["avg_stars"] = $row->avg_stars;
			$this->getResult()->addValue(null, $i, $page);
			$i++;
		}
	}
}
