<?php


class ApiLastEvents extends ApiBase {
	public function execute() {
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
            $dbr = wfGetDB( DB_REPLICA );
            $res = $dbr->query("SELECT * FROM `ag_last_events` WHERE ag_timestamp > '$from'");
            foreach ($res as $row) {
                if(preg_replace("/[^0-9]/", "",$row->ag_timestamp) > $latest)
                    $latest=$row->ag_timestamp;
                array_push($events, $row->ag_event);
            }
            $latest = preg_replace("/[^0-9]/", "",$latest);
            $f = function($e){
                switch ($e) {
                    case 'login':
                        return 0;
                        break;
                    case 'save_page':
                        return 1;
                        break;
                    default:
                        $this->dieWithError(ApiMessage::create("No se pudo decodificar el evento de la BD."));
                        break;
                }
            };
            $events = array_map($f, $events);
        }
        $this->getResult()->addValue(null, "events", $events);
        $this->getResult()->addValue(null, "latest", $latest);
    }
    public function getAllowedParams() {
		return [
			'from' => [ ApiBase::PARAM_TYPE => 'string' ]
		];
	}
}