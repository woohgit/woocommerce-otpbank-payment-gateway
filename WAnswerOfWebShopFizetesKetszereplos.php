<?php


require_once 'WebShopFizetesValasz.php';
require_once 'WebShopXmlUtils.php';

/**
* K�tszerepl�s fizet�s illetve k�tl�pcs�s fizet�s lez�r�s 
* v�lasz XML-j�nek feldolgoz�s�sa �s a megfelel� value object el��ll�t�sa.
* 
* @version 4.0
*/
class WAnswerOfWebShopFizetesKetszereplos {

    /**
    * K�tszerepl�s fizet�s illetve k�tl�pcs�s fizet�s lez�r�s 
    * v�lasz XML-j�nek feldolgoz�s�sa �s a megfelel� value object el��ll�t�sa.
    * 
    * @param DomDocument $answer A tranzakci�s v�lasz xml
    * @return WebShopFizetesValasz a v�lasz tartalma, 
    *         vagy NULL �res/hib�s v�lasz eset�n
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
