<?php

require_once 'RequestUtils.php';


/**
* Konfigurációs paraméterek kezelésének támogatása.
* Egyaránt kezeli a simpleshop és multishop környezet szerint kialakított
* .config állományokat.
* 
* @version 4.0
*/
class ConfigUtils {

    /**
    * @desc Konfigurációs paraméter kiolvasása simpleshop
    * vagy multishop környezet szerint kialakított
    * .config állományból
    * 
    * @param Array config az konfigurációs fájl tartalma
    * @param String paramName a keresett konfigurációs paraméter neve
    * @return string a paraméter értéke vagy null
    */
    function safeConfigParam($config, $paramName) {
        return array_key_exists($paramName, $config) ? $config[$paramName] : null;
    }
        
    /**
    * @desc Konfigurációs paraméter kiolvasása simpleshop
    * vagy multishop környezet szerint kialakított
    * .config állományból
    * 
    * @param Array config az konfigurációs fájl tartalma
    * @param String paramName a keresett konfigurációs paraméter neve
    * @param String posId a vonatkozó posId
    * @param mixed config a keresett konfigurációs paraméter alapértelmezett értéke 
    *   (arra az esetre, ha nem létezik a megfelelõ paraméter)
    * @return mixed a paraméter értéke string vagy $defaultValue-val megegyezõ típusban
    */
    function getConfigParam($config, $paramName, $posId = NULL, $defaultValue = NULL) {
        $paramValue = NULL;
        if (!is_null($posId)) {
            $paramValue = ConfigUtils::safeConfigParam($config, $paramName . '_' . $posId);
        }
        if (is_null($paramValue)) {
            $paramValue = ConfigUtils::safeConfigParam($config, $paramName);
        }
        if (is_null($paramValue)) {
            $paramValue = $defaultValue;
        }
        return $paramValue;
    }

    /**
    * @desc Logikai értékû konfigurációs paraméter kiolvasása simpleshop
    * vagy multishop környezet szerint kialakított
    * .config állományból
    * 
    * @param Array $config az konfigurációs fájl tartalma
    * @param String $paramName a keresett konfigurációs paraméter neve
    * @param String $posId a vonatkozó posId
    * @param mixed $defaultValue a keresett konfigurációs paraméter alapértelmezett értéke 
    *   (arra az esetre, ha nem létezik a megfelelõ paraméter)
    * 
    * @return boolean a paraméter (vagy de$faultValue) logikai értéke
    */
    function getConfigParamBool($config, $paramName, $posId = NULL, $defaultValue = NULL) {
        $paramValue = ConfigUtils::getConfigParam($config, $paramName, $posId = NULL, $defaultValue = NULL);
        return RequestUtils::getBooleanValue($paramValue);
    }
    
    /**
    * @desc A Java-ra jellemzõ konfigurációs paraméter helyettesítés 
    * megvalósítása: a $paramValue string-ben szereplõ {key} értékek
    * lecserélése a $values[key] szövegre.
    * 
    * @param string paramValue a helyettesítésre váró szöveg
    * @param array a helyettesítések key/value párban$
    * 
    * @return string a helyettesített szöveg 
    */
    public static function substConfigValue($paramValue, $values) {
        foreach ($values as $key => $value) {
            $paramValue = str_replace("{" . $key ."}", $value, $paramValue);
        }
        return $paramValue;       
    }
    
}

?>
