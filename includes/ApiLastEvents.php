<?php


class ApiLastEvents extends ApiBase {
	public function execute() {
        $params = $this->extractRequestParams();
        if(!isset($params["from"])){
            $this->dieWithError(ApiMessage::create("Se necesita el parametro from"));
        }
        $from = $params['from'];
        //SELECT * FROM `ag_last_events` WHERE ag_timestamp > '$from'
        $dbr = wfGetDB( DB_REPLICA );
        $res = $dbr->query("SELECT * FROM `ag_last_events` WHERE ag_timestamp > '$from'");
        $events = [];
        $latest = $from;
        foreach ($res as $row) {
            ($row->ag_timestamp > $latest)?$latest=$row->ag_timestamp:$latest=$latest;
            array_push($events, $row->ag_event);
        }
        $this->getResult()->addValue(null, "events", $events);
        $this->getResult()->addValue(null, "latest", $latest);
        //$this->getResult()->addValue(null, "time", date($from));        
    }
    public function getAllowedParams() {
		return [
			'from' => [ ApiBase::PARAM_TYPE => 'string' ]
		];
	}
}