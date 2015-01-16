<?php

require_once 'WebShopKulcsAdatokLista.php';
require_once 'WebShopKulcsAdatok.php';
require_once 'WebShopXmlUtils.php';

/**
* Kulcs lekérdezés válasz XML-jének feldolgozásása és
* a megfelelõ value object elõállítása.
* 
* @author Lászlók Zsolt
* @version 4.0
*/
class WAnswerOfWebShopKulcsLekerdezes {

    /**
    * @desc A banki felület által visszaadott szöveges logikai
    * értékbõl boolean típusú érték elõállítása.
    * 
    * A képzés módja:
    * "TRUE" szöveges érték => true logikai érték
    * minden más érték => false logikai érték
    */
    function getBooleanValue($value) {
      $result = false;
      
      if (!is_null($value) && strcasecmp("TRUE", $value) == 0) {
        $result = true;
      }
      
      return $result;
    }

    /**
    * Kulcs lekérdezés válasz XML-jének feldolgozásása és
    * a megfelelõ value object elõállítása.
    * 
    * @param DomDocument $answer A tranzakciós válasz xml
    * @return WebShopKulcsAdatokLista a válasz tartalma, 
    *         vagy NULL üres/hibás válasz esetén
    */
    function load($answer) {
    	    	
    	$webShopKulcsAdatokLista = new WebShopKulcsAdatokLista();
        $resultSet = WebShopXmlUtils::getNodeByXPath($answer, '//answer/resultset');
                
        if(!empty($resultSet)) {
        	$webShopKulcsAdatokLista->setPrivateKey(WebShopXmlUtils::getElementText($resultSet, 'privateKey'));
        }
        
        $recordList = WebShopXmlUtils::getNodeArrayByXPath($answer, '//answer/resultset/record');
        $lista = array();
        
        foreach ($recordList as $record) {
        	
            $webShopKulcsAdatok = new WebShopKulcsAdatok();
            
            $webShopKulcsAdatok->setLejarat(WebShopXmlUtils::getElementText($record, 'lejarat'));
            
            $lista[] = $webShopKulcsAdatok;
        }
        
        $webShopKulcsAdatokLista->setWebShopKulcsAdatok($lista);
        
        return $webShopKulcsAdatokLista;
    }

}

?>