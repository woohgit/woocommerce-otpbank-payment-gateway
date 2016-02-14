<?php

require_once 'WebShopJovairasValasz.php';
require_once 'WebShopXmlUtils.php';

/**
* Fizetés jóváírás válasz XML-jének feldolgozásása és a megfelelõ value object elõállítása.
* 
* @version 4.0
*/
class WAnswerOfWebShopJovairas {

    /**
    * Fizetés jóváírás válasz XML-jének feldolgozásása és a megfelelõ value object elõállítása.
    * 
    * @param DomDocument $answer A tranzakciós válasz xml
    * @return WebShopJóváírásValasz a válasz tartalma, 
    *         vagy NULL üres/hibás válasz esetén
    */
    function load($answer) {
        $webShopJovairasValasz = new WebShopJovairasValasz();
       
        $record = WebShopXmlUtils::getNodeByXPath($answer, '//answer/resultset/record');
        if (!is_null($record)) {
            $webShopJovairasValasz->setMwTransactionId(WebShopXmlUtils::getElementText($record, "mwTransactionId"));
            $webShopJovairasValasz->setValaszKod(WebShopXmlUtils::getElementText($record, "responsecode"));
            $webShopJovairasValasz->setAuthorizaciosKod(WebShopXmlUtils::getElementText($record, "authorizationcode"));
        }
        
        return $webShopJovairasValasz;
    }

}

?>