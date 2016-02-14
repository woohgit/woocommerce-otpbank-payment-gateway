<?php

require_once 'DefineConst.php';

/**
* A SimpleShop PHP és a WebShop PHP kliens által használt utility osztály
* XML szövegek és DomDocument objektumok kezelésére, PHP5 környezetben.
* 
* @version 4.0
*/

class WebShopXmlUtils {

    /**
    * @desc Banki tranzakcióhoz tartozó üres input xml váz létrehozása:
    * <StartWorkflow>
    *   <TemplateName>$templateName</TemplateName>
    *   <Variables/>
    * </StartWorkflow>
    * 
    * @param string $templateName az indíandó tranzakció neve (szöveges kódja)
    * @param DomNode $variables referencia az input xml-ben létrehozott
    * Varaibles tag-re.
    * 
    * @return DomDocument a létrehozott objektum
    */
    public static function getRequestSkeleton($templateName, &$variables) {
        $dom = new DomDocument('1.0', WF_INPUTXML_ENCODING);
        $root = $dom->createElement('StartWorkflow');
        $dom->appendChild($root);

        $root->appendChild($dom->createElement('TemplateName', $templateName));

        $variables = $dom->createElement('Variables');
        $root->appendChild($variables);

        return $dom;
    }

    /**
    * @desc Banki tranzakcióhoz tartozó input xml kiegészítése egy 
    * input változó értékkel. A változó értéknek olyan karakter
    * kódolásúnak kell lennie, ami a WS_CUSTOMERPAGE_CHAR_ENCODING
    * konstansban definiálásra került (a default érték az ISO-8859-2).
    * 
    * Megj: PHP 4.x alatt a namespace-t nem támogatja a metódus!
    * 
    * @param DomDocument $dom maga az input xml
    * @param DomNode $variables az xml Variables tag-je
    * @param string name a beillesztendõ változó neve
    * @param string value a beillesztendõ változó értéke.
    * @param string attributeName a változóhoz esetlegesen hozzáadandó attribútum neve
    * @param string attributeValue a változóhoz esetlegesen hozzáadandó attribútum értéke
    */
    public static function addParameter($dom, $variables, $name, $value, $attributeName = null, $attributeValue = null) {
        $node = null;
        if (is_bool($value)) {
            $value = $value ? REQUEST_TRUE_CONST : REQUEST_FALSE_CONST;
        }
        
        if (!is_null($value)) {
            $value = iconv(WS_CUSTOMERPAGE_CHAR_ENCODING, $dom->actualEncoding, $value);            
        }
        
        $attribute = null;
        
        if ($dom->documentElement->namespaceURI) {
            $node = $dom->createElementNS($dom->documentElement->namespaceURI, $name);
            $node->prefix = $dom->documentElement->prefix;
            
            if (!empty($attributeName) && !empty($attributeValue)) {
            	$attribute = $dom->createAttributeNS($attributeName);
            	$attribute->prefix = $dom->documentElement->prefix;
            }
            
        }
        else {
            $node = $dom->createElement($name);
            
            if (!empty($attributeName) && !empty($attributeValue)) {
            	$attribute = $dom->createAttribute($attributeName);
            }
        }
        
        if (!is_null($attribute)) {
        	$attribute->value = $attributeValue;
        	$node->appendChild($attribute);
        }
        
        $node->appendChild($dom->createTextNode($value));
        
        $variables->appendChild($node);
    }

    /**
    * @desc Adott xml node elsõ adott nevû child node-jának lekérdezése.
    * 
    * @param DomNode $record
    * @param string $childName
    * 
    * @return DomNode az adott nevû Node / Element vagy NULL
    */
    public static function getChildElement($record, $childName) {
        $result = NULL;
        $childNodes = $record->childNodes;
        for($i = 0; !is_null($childNodes) && $i<= $childNodes->length && is_null($result); $i++){
            $item = $childNodes->item($i);
            if (isset($item->nodeName) && $item->nodeName == $childName) 
                $result = $childNodes->item($i);
        }
        return $result;
    }
    
    /**
    * @desc Adott xml node adott nevû child node-ja szöveges 
    * tartalmának lekérdezése. Az eredmény összefûzve tartalmazza 
    * az XML_TEXT_NODE típusú child node-k értékét.
    * 
    * @param DomNode $record a szülõ node
    * @param string $childNode a child node neve
    * 
    * @return string a child node szöveges tartalma
    */
    public static function getElementText($record, $childName) {
        $result = NULL;
        $childNode = self::getChildElement($record, $childName);
        if (!is_null($childNode)) $result = $childNode->textContent;
        return iconv($record->ownerDocument->actualEncoding, WS_CUSTOMERPAGE_CHAR_ENCODING, $result);
    }

    /**
    * @desc XPath kifejezés kiértékelése, mely egy
    * adott elemtõl indul és egy elemre vonatkozik. 
    * Lista esetén az elsõ elem kerül a válaszba.
    * 
    * @param DOMDocument / DOMNode $node a kiértékelés helye
    * @param string $xpath xpath kifejezés
    */
    public static function getNodeByXPath($node, $xpath) {
        $doc = NULL;
        if (is_a($node, 'DOMDocument')) {
            $doc = $node;
            $node = $node->documentElement;   
        }
        else {
            $doc = $node->ownerDocument;
        }
        
        $path = new DOMXPath($doc);
        $record = $path->query($xpath, $node);

        if (is_a($record, 'DOMNodeList') && ($record->length > 0)) 
            $record = $record->item(0);

        return $record;
    }

    /**
    * @desc XPath kifejezés kiértékelése, mely egy
    * adott elemtõl indul és elemekre listájára vonatkozik. 
    * 
    * @param DOMDocument / DOMNode $node a kiértékelés helye
    * @param string $xpath xpath kifejezés
    */
    function getNodeArrayByXPath($node, $xpath) {
        $doc = NULL;
        if (is_a($node, 'DOMDocument')) {
            $doc = $node;
            $node = $node->documentElement;   
        }
        else {
            $doc = $node->ownerDocument;
        }
        
        $path = new DOMXPath($doc);
        $recordList = $path->query($xpath, $node);

        $result = array();
        if (is_a($recordList, 'DOMNodeList')) {
            for ($i=0; $i < $recordList->length; $i++) {
                $result[] = $recordList->item($i);
            }
        }
        else if (is_a($recordList, 'DOMNode')) {
            $result[] = $recordList;
        }

        return $result;
    }
    
    /**
    * @desc A banki tranzakció output xml-jének értelmezése, 
    * adott WResponse objektum feltöltése.
    * 
    * @param string $responseStr output xml szövege
    * @param WResponse feltöltendõ response objektum
    */
    public static function parseOutputXml ($responseStr, $wresponse) {
        $wresponse->response = $responseStr;
        $wresponse->responseDOM = new DomDocument();
        $wresponse->responseDOM->loadXML($wresponse->response);
        
        $path = new DOMXPath($wresponse->responseDOM);
        
        // Válaszkódok listájának elõállítása
        $wresponse->hasSuccessfulAnswer = false;
        $messageElements = $path->query('//answer/messagelist/message');
        for ($i = 0; $i < $messageElements->length; $i++) {
            $messageElement = $messageElements->item($i);
            $message = $messageElement->nodeValue;
            $wresponse->messages[] = $message;
            if ($message != WF_SUCCESS_TEXTS) {
                $wresponse->errors[] = $message;
            }
            else {
                $wresponse->hasSuccessfulAnswer = true;
            }
        }

        // Tájékoztató kódok listájának elõállítása
        $infoElements = $path->query('//answer/infolist/info');
        for ($i = 0; $i < $infoElements->length; $i++) {
            $infoElement = $infoElements->item($i);
            $info = $infoElement->nodeValue;
            $wresponse->infos[] = $info;
        }
    }
  
    /**
    * DomDocument szöveges reprezentációja
    * 
    * @param DomDocument $dom 
    * 
    * @return string $dom->saveXML()
    */
    public static function xmlToString($dom) {
        return $dom->saveXML();
    }
  
}

?>