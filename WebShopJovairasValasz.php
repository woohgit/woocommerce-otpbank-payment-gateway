<?php

/**
* @desc A fizetés jóváírás tranzakció válaszadatait
* tartalmazó bean / value object.
* 
* @version 4.0
*/
class WebShopJovairasValasz {

    /**
    * @desc A fizetés jóváírás tranzakció bank oldali egyedi tranzakció azonosítója.
    * 
    * @var string
    */
    var $mwTransactionId;

    /**
    * @desc A válaszkód a jóváírási tranzakció „eredménye”. 
    * Sikeres jóváírás esetén egy háromjegyû numerikus kód a 000-010 értéktartományból. 
    * Sikertelen vásárlás esetén, amennyiben a hiba (vagy elutasítás) a jóváírás mûvelete során történik 
    * (a kártyavezetõ rendszerben), szintén egy háromjegyû numerikus kód jelenik meg, mely a 010 értéknél nagyobb. 
    * Egyéb hiba (vagy elutasítás) esetén a válaszkód egy olyan alfanumerikus "olvasható" kód, 
    * mely a hiba (vagy elutasítás) okát adja meg.
    * 
    * @var string
    */
    var $valaszKod;
    
    /**
    * @desc Authorizációs kód, a POS-os jóváíráshoz tartozó authorizációs engedély szám. 
    * Csak sikereses jóváírási tranzakciók esetén kerül kitöltésre. 
    * Az adat a kártyavezetõ rendszer válasza a  jóváíráshoz tartozó kártyajóváírási mûvelethez, 
    * egyfajta azonosító / hitelesítõ kód, s mint ilyen, a bolt is megkapja válaszadatként.
    */
    var $authorizaciosKod;
    
    var $posId; // peti 2011-03-28
    var $azonosito; // peti 2011-03-28
    var $teljesites; // peti 2011-03-28
    

    function getMwTransactionId() {
        return $this->mwTransactionId;
    }

    function setMwTransactionId($mwTransactionId) {
        $this->mwTransactionId = $mwTransactionId;
    }

    //
    // peti 2011-03-28
    function getPosId() { 
        return $this->posId;
    }
    // peti 2011-03-28
    function setPosId($pos) {
        $this->posId = $pos;
    }
    //
    //
    // peti 2011-03-28
    function getAzonosito() {
        return $this->azonosito;
    }
    // peti 2011-03-28
    function setAzonosito($azon) {
        $this->azonosito = $azon;
    }

    // peti 2011-03-28
    function getTeljesites() {
        return $this->teljesites;
    }

    // peti 2011-03-28
    function setTeljesites($teljesites) {
        $this->teljesites = $teljesites;
    }



    //

    function getValaszKod() {
        return $this->valaszKod;
    }

    function setValaszKod($valaszKod) {
        $this->valaszKod = $valaszKod;
    }

    function getAuthorizaciosKod() {
        return $this->authorizaciosKod;
    }

    function setAuthorizaciosKod($authorizaciosKod) {
        $this->authorizaciosKod = $authorizaciosKod;
    }

}

?>
