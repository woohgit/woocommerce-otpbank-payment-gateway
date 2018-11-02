<?php

/**
* Kulcslekérdezõ kérés válasz adatait tartalmazó
* value object. A WEBSHOPKULCSLEKERDEZES tranzakciós válasz xml
* feldolgozásakor keletkezik, válasz tételenként egy darab.
* 
* @author Lászlók Zsolt
* @version 4.0
*/
class WebShopKulcsAdatok {

	/**
    * Lejárat dátuma, vagy hibaüzenet
    * 
    * @var string
    */
    var $lejarat;
    
    function getLejarat() {
        return $this->lejarat;
    }

    function setLejarat($lejarat) {
        $this->lejarat = $lejarat;
    }
}
