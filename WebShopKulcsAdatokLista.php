<?php

/**
* A WEBSHOPKULCSLEKERDEZES tranzakciós válasz xml-t
* reprezentáló value object.
* 
* @author Lászlók Zsolt
* @version 4.0
*/
class WebShopKulcsAdatokLista {

    /**
    * Generált kulcs adatok zip-elt, base64 kódolt formátumban
    * 
    * @var string
    */
    var $privateKey;

    /**
    * A lekérdezett kulcsadatokat reprezentáló
    * WebShopKulcsAdatok objektumok listája.
    * 
    * @var array
    */
    var $webShopKulcsAdatok;

    function getPrivateKey() {
        return $this->privateKey;
    }

    function setPrivateKey($privateKey) {
        $this->privateKey = $privateKey;
    }

    function getWebShopKulcsAdatok() {
        return $this->webShopKulcsAdatok;
    }

    /**
    * @desc Kulcs adatok tömb tárolása
    */
    function setWebShopKulcsAdatok(&$webShopKulcsAdatok) {
        $this->webShopKulcsAdatok = $webShopKulcsAdatok;
    }

}

?>