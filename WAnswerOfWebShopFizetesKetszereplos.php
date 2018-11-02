<?php


require_once 'WebShopFizetesValasz.php';
require_once 'WebShopXmlUtils.php';

/**
* Kétszereplõs fizetés illetve kétlépcsõs fizetés lezárás 
* válasz XML-jének feldolgozásása és a megfelelõ value object elõállítása.
* 
* @version 4.0
*/
class WAnswerOfWebShopFizetesKetszereplos {

    /**
    * Kétszereplõs fizetés illetve kétlépcsõs fizetés lezárás 
    * válasz XML-jének feldolgozásása és a megfelelõ value object elõállítása.
    * 
    * @param DomDocument $answer A tranzakciós válasz xml
    * @return WebShopFizetesValasz a válasz tartalma, 
    *         vagy NULL üres/hibás válasz esetén
    */
    function load($answer) {
        $webShopFizetesValasz = new WebShopFizetesValasz();
       
        $record = WebShopXmlUtils::getNodeByXPath($answer, '//answer/resultset/record');
        if (!is_null($record)) {
            $webShopFizetesValasz->setPosId(WebShopXmlUtils::getElementText($record, "posid"));
            $webShopFizetesValasz->setAzonosito(WebShopXmlUtils::getElementText($record, "transactionid"));
            $webShopFizetesValasz->setTeljesites(WebShopXmlUtils::getElementText($record, "timestamp"));
            $webShopFizetesValasz->setValaszKod(WebShopXmlUtils::getElementText($record, "posresponsecode"));
            $webShopFizetesValasz->setAuthorizaciosKod(WebShopXmlUtils::getElementText($record, "authorizationcode"));
        }
        
        return $webShopFizetesValasz;
    }

}
