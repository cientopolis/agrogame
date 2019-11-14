<?php


class ApiLastEvents extends ApiBase {
	public function execute() {
        global $wgStateValue;
        $params = $this->extractRequestParams();
        if(!isset($params["from"])){
            $this->dieWithError(ApiMessage::create("Se necesita el parametro from"));
        }
        $from = $params['from'];
        $events = [];
        $latest = $from;
        if($from == "00000000000000"){
            $latest = gmdate("YmdHis");
        }else{
            $this->getEventsAndRefreshLastEvent($events, $latest, $from);
        }
        $this->getResult()->addValue(null, "events", $events);
        $this->getResult()->addValue(null, "latest", $latest);
        $this->getResult()->addValue(null, "state", $wgStateValue);
    }
    public function getAllowedParams() {
		return [
			'from' => [ ApiBase::PARAM_TYPE => 'string' ]
		];
    }
    
    private function getEventsAndRefreshLastEvent(&$events, &$latest, $from){
        $dbr = wfGetDB( DB_REPLICA );
        $res = $dbr->query("SELECT replace(replace(ag_event,'login',0),'save_page',1) as  ag_event, replace(replace(replace(ag_timestamp,'-',''),' ',''),':','') as ag_timestamp FROM `ag_last_events` WHERE ag_timestamp > '$from'");
        foreach ($res as $row) {
            if($row->ag_timestamp > $latest){
                $latest=$row->ag_timestamp;
            }
            array_push($events, (int)$row->ag_event);
        }
    }
}