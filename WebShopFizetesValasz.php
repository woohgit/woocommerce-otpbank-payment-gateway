<?php

/**
* @desc A kétszereplõs fizetési tranzakció illetve 
* a kétlépcsõs fizetés lezárása tranzakció válaszadatait
* tartalmazó bean / value object.
* 
* @version 4.0
*/
class WebShopFizetesValasz {

    /**
    * @desc Shop azonosító, mely megegyezik a inputban megadott értékkel.
    * 
    * @var string
    */
    var $posId;
    
    /**
    * @desc Fizetési tranzakció azonosító, megegyezik az inputban megadott fizetési tranzakció azonosítóval.
    * 
    * @var string
    */
    var $azonosito;

    /**
    * @desc A teljesítés idõpecsétje megadja a fizetési tranzakció végének (lezárásának) idõpontját. 
    * Sikeres és sikertelen vásárlások esetén is kitöltésre kerül.
    * 
    * @var string
    */
    var $teljesites;

    /**
    * @desc A válaszkód a fizetési tranzakció „eredménye”. 
    * Sikeres vásárlás esetén egy háromjegyû numerikus kód a 000-010 értéktartományból. 
    * Sikertelen vásárlás esetén, amennyiben a hiba (vagy elutasítás) a terhelés mûvelete során történik 
    * (a kártyavezetõ rendszerben), szintén egy háromjegyû numerikus kód jelenik meg, mely a 010 értéknél nagyobb. 
    * Egyéb hiba (vagy elutasítás) esetén a válaszkód egy olyan alfanumerikus "olvasható" kód, 
    * mely a hiba (vagy elutasítás) okát adja meg.
    * 
    * @var string
    */
    var $valaszKod;
    
    /**
    * @desc Authorizációs kód, a POS-os vásárláshoz tartozó authorizációs engedély szám. 
    * Csak sikereses vásárlási tranzakciók esetén kerül kitöltésre. 
    * Az adat a kártyavezetõ rendszer válasza a  vásárláshoz tartozó kártyaterhelési mûvelethez, 
    * egyfajta azonosító / hitelesítõ kód, s mint ilyen, a vevõ oldali felületen is megjelenik, 
    * valamint a bolt is megkapja válaszadatként. Ez a kód mindkét fél számára tárolandó adat!
    */
    var $authorizaciosKod;

    function getPosId() {
        return $this->posId;
    }

    function setPosId($posId) {
        $this->posId = $posId;
    }

    function getAzonosito() {
        return $this->azonosito;
    }

    function setAzonosito($azonosito) {
        $this->azonosito = $azonosito;
    }

    function getTeljesites() {
        return $this->teljesites;
    }

    function setTeljesites($teljesites) {
        $this->teljesites = $teljesites;
    }

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