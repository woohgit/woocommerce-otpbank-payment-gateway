<?php 

require_once 'WSAnswerFactory.php';
require_once 'DefineConst.php';
require_once 'WebShopXmlUtils.php';

/**
* A banki tranzakció válaszát reprezentáló value object.
* Tartalmazza az indított tranzakciók bank oldali egyedi azonosítóját,
* a válasz xml szöveges és DomDocument formáját, a válasz xml annak 
* feldolgozásából nyert value object reprezentációját, valamint a válasz-
* üzenetek listáját.
* 
* @version 4.0
*/
class WResponse  {

    /***************
     * Folyamat-példány bank oldali azonosítója
     * 
     * @var string
     */
    var $instanceId = NULL;

    /***************
     * A válasz teljes és eredeti szövege
     * 
     * @var string
     */
    var $response = NULL;

    /***************
     * A válasz xml teljes és eredeti szövegének DOM-ja
     * 
     * @var DomDocument
     */
    var $responseDOM = NULL;

    /***************
     * A válasz xml value object formában
     * 
     * @var mixed
     */
    var $answerModel = NULL;

    /***************
     * A válasz feldolgozása során kinyert üzenetkódok vektora.
     * Sikeres végrehajtás esetén csak a 'SIKER' szöveget tartalmazza,
     * hibás lefutás esetén az egyes hibakódokat.
     * 
     * @var array
     */
    var $messages = array();

    /***************
     * A válasz feldolgozása során kinyert hibakódok vektora
     * Sikeres végrehajtás esetén üres,
     * hibás lefutás esetén az egyes hibakódokat.
     * 
     * @var array
     */
    var $errors = array();
    
    /***************
     * A válasz feldolgozása során kinyert információs üzenetkódok vektora.
     * 
     * @var array
     */
    var $infos = array();

    /***************
     * Sikeres volt-e a tranzakció végrehajtása, azaz az üzenetkódok 
     * listája tartalmazza-e a 'SIKER' szöveget.
     * 
     * @var boolean
     */
    var $hasSuccessfulAnswer = NULL;
    
    /***************
     * Végrehajtódott-e a tranzakció és rendelkezésre áll-e a válaszadat.
     * Kommunikációs, timeout vagy egyéb hiba esetén false az értéke
     * 
     * @var boolean
     */
    var $finished = NULL;
    
    /**
    * Konstruktor.
    * 
    * @param string $workflowname az indított tranzakció kódja
    * @param WorkflowState $workflowState a banki SOAP felület válaszának
    *        bean reprezenzációja
    */
    function WResponse ($workflowname, $workflowState) {
        if (is_null($workflowState)) return;
        $this->instanceId = $workflowState->instanceId;
        $this->finished = ($workflowState->completed && ! $workflowState->timeout);

        if ($this->finished) {
            WebShopXmlUtils::parseOutputXml($workflowState->result, $this);
            $answerFactory = WSAnswerFactory::getAnswerFactory($workflowname);
            $this->answerModel = $answerFactory->load($this->responseDOM);
        }    
    }

    /**
    * @desc WResponse feltöltése value object alapján 
    */
    function loadAnswerModel($answerModel, $successful, $errorMsg = null) {
        $this->finished = true;
        $this->answerModel = $answerModel;
        $this->hasSuccessfulAnswer = $successful;
        if ($successful) {
            $this->messages[] = "SIKER";
        }
        else {
            if (!is_null($errorMsg)) {
                $this->errors[] = $errorMsg;
                $this->messages[] = $errorMsg;
            }
        }
    }
    
    /***************
     * Eldönti, hogy a folyamat-példány válaszának messagelist szekciója
     * tartalmaz-e hibaüzenetet.
     * Ha a folyamat nem terminált, vagy a válasz nem megszerezhetõ, a válasz hamis.
     *
     * @return igaz, ha a hibaüzenetek listája nem üres
     */
    function hasErrors() {
        return count($this->messages) > 0;
    }

    /***************
     * A folyamat-példány válaszában a messagelist szekciónak elemzett 
     * listájának lekérdezése. "Elemzett", mert a SIKER kódot nem 
     * tartalmazza: sikeres lefutás esetén ez a lista üres.
     * 
     * @return array üzenetkód lista
     */
    function getMessages() {
        return $this->messages;
    }
    
    /***************
     * A folyamat-példány válaszában a messagelist szekciónak elemzett 
     * listájának lekérdezése. "Elemzett", mert a SIKER kódot nem 
     * tartalmazza: sikeres lefutás esetén ez a lista üres.
     * 
     * @return array hibakód lista
     */
    function getErrors() {
        return $this->errors;
    }

    /***************
     * A folyamat-példány válaszában az infolist szekciónak az elemzését és
     * betöltését végzõ metódus.
     * 
     * @return array információs üzenetkód lista
     */
    function getInfos() {
        return $this->infos;
    }

    /***************
     * Igaz, ha a tranzakció-futás befejezõdött.
     * Ha true, akkor a válasz lehet sikeres vagy hibás lefutású is.
     * Ha  false, akkor kommunikációs vagy egyéb hiba történt a 
     * tranzakció végrehajtása vagy a válasz fogadása során.
     *
     * @return boolean igaz, ha a tranzaktálás befejezõdött
     */
    function isFinished() {
        return $this->finished;
    }

    /***************
     * A folyamat-példány sikeres befejezõdésének megállapítása
     * A folyamat sikeresen terminált, ha a válasz <messagelist> részében szerepel
     * a SIKER üzenet.
     * Ha a folyamat nem terminált, vagy a válasz nem megszerezhetõ, a válasz hamis.
     *
     * @return boolean a sikeresség jelzõje
     */
    function isSuccessful()  {
        return $this->hasSuccessfulAnswer;
    }

    /***************
     * A folyamat-példány banki oldali példány-azonosítójának kiolvasása
     *
     * @return string példányazonosító (null, ha nincs megszerezve a példány)
     */
    function getInstanceId() {
        return $this->instanceId;
    }

    /***************
     * A végrehajtáshoz tartozó válasz xml, a megfelelõ value object reprezentációban
     *
     * @return mixed a válasz bean konténerte
     */
    function getAnswer() {
        return $this->answerModel;
    }

    /***************
     * A végrehajtáshoz tartozó válasz xml, string reprezentációban
     *
     * @return string a válasz xml
     */
    function getResponse() {
        return $this->response;
    }

    /***************
     * A végrehajtáshoz tartozó válasz xml, DomDocument formájában
     *
     * @return DomDoument a válasz 
     */
    function getResponseDOM() {
        return $this->responseDOM;
    }

}

?>
