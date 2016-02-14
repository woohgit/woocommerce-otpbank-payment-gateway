<?php

/**
* @desc A banki SOAP felület startWorkflowSnyc tranzakció indítás 
* szolgáltatásának visszatérési értékét reprezentáló objektum.
* 
* Az NuSoap használata miatt került kialakításra, ugyanis az ottani SOAP
* kliens nem objektumként adja vissza a szolgáltatás eredményét,
* hanem asszociatív tömbként. Ezt a tömböt lehet megadni az objektum
* egyik konstruktorának.
* 
* @version 4.0
*/
class WorkflowState {

    var $completed;
    var $timeout;
    var $startTime;
    var $endTime;
    var $result;
    var $instanceId;
    var $templateName;

    /**
    * @desc Asszociatív tömb betöltése WorkflowState objektumba.
    * 
    * @param array $stateAsArray betöltendõ asszociatív tömb
    */
    function WorkflowState($stateAsArray) {
        if (is_null($stateAsArray)) return;
        
        $this->completed = $stateAsArray['completed'];
        $this->timeout = $stateAsArray['timeout'];
        $this->startTime = $stateAsArray['startTime'];
        $this->endTime = $stateAsArray['endTime'];
        $this->result = $stateAsArray['result'];
        $this->instanceId = $stateAsArray['instanceId'];
        $this->templateName = $stateAsArray['templateName'];
    }

}