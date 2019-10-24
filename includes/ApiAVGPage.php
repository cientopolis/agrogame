<?php


class ApiAVGPage extends ApiBase {
	public function execute() {
        $params = $this->extractRequestParams();
        if(!isset($params["pagetitle"])){
            $this->dieWithError(ApiMessage::create("Se necesita el parametro title"));
        }
        $title = Title::newFromText( $params['pagetitle'] );
        //SELECT `rv_page_id`, avg(`rv_answer`) FROM ratepage_vote WHERE rv_page_id = idpag GROUP BY `rv_page_id`
        $dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
            "ratepage_vote",
            array("rv_page_id", "avg(rv_answer) as avg"),
            "rv_page_id = " . $title->getArticleID(),
            __METHOD__,
            array("GROUP BY" => "rv_page_id")
        );
        if ($res->numRows() == 0){
            $this->dieWithError(ApiMessage::create("No existe pagina"));
        }else{
            $this->getResult()->addValue(null, "title", $title);
            //$this->getResult()->addValue(null, "page_id", $res->current()->rv_page_id); no exponer ID de la pag.
            $this->getResult()->addValue(null, "avg", $res->current()->avg);
        }
    }
    public function getAllowedParams() {
		return [
			'pagetitle' => [ ApiBase::PARAM_TYPE => 'string' ]
		];
	}
}