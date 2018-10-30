<?php
     
/**
* A SimpleShop PHP és a WebShop PHP kliens által használt utility osztály
* a REQUEST paraméterek kezelésére, típus-konverziók elvégzésére, URL manipulációra.
* 
* @version 4.0
*/

class RequestUtils {

    /**
     * Logikai típusú request paraméter kiolvasása,
     * és boolean értékké alakítása.
     * Lehetséges true értékek: 'true', 'on', 'yes'.
     * (Kis- és nagybetû érzékenység nincs.)
     * 
     * @param request http request (paraméter vektor)
     * @param paramName a paraméter neve
     * @return a paraméter szöveges értéke
     */
    function getBooleanRequestAttribute($paramMap, $paramName, $dflt = false) {
        return RequestUtils::getBooleanValue($paramMap[$paramName]);        
    }

    /**
     * Logikai típusú request paraméter kiolvasása,
     * és boolean értékké alakítása.
     * Lehetséges true értékek: 'true', 'on', 'yes', '1'.
     * (Kis- és nagybetû érzékenység nincs.)
     * 
     * @param request http request (paraméter vektor)
     * @param paramName a paraméter neve
     * @return a paraméter szöveges értéke
     */
    public static function getBooleanValue($value, $dflt = false) {
        $boolValue = false;
        
        if (is_bool($value)) {
            $boolValue = $value;
        }
        else if (is_null($value)) {
            $boolValue = $dflt;
        }
        else {
            $boolValue = in_array(strtoupper($value), array ("TRUE", "ON", "YES", "1"));
        }

        return $boolValue;        
    }
    
    /**
     * Logikai típusú változó érték olyan string-é alakítása,
     * mely a banki felületen a logikai értéket reprezentálja az egyes
     * hívásokban: "TRUE" vagy "FALSE" érték.
     * 
     * Az alakítás szabálya:
     * - false érték: "FALSE"
     * - true vagy "true", "on", "yes" értékek valamelyike: "TRUE"
     * - egyébként: a $dflt változó értéke, alapértelmezés szerint "FALSE"
     * 
     * @param mixed value a logikai vagy string változó
     * @return string a paraméter szöveges értéke
     */
    public static function booleanToString($value, $dflt = "FALSE") {
        $boolValue = RequestUtils::getBooleanValue($value, NULL);
        return ($boolValue === true ? "TRUE" : ($boolValue === false ? "FALSE" : $dflt));        
    }

    /**
     * Date típusú változó érték olyan string-é alakítása,
     * mely a banki felületen a dátum/idõpont értéket reprezentálja az egyes
     * hívásokban: ÉÉÉÉ.HH.NN ÓÓ:PP:MM alakú érték.
     * 
     * @param mixed value a dátum változó
     * @return string a paraméter szöveges értéke
     */
    public static function dateToString($value) { // wp-hack: added "public static"
        $strValue = false;
        
        if (is_string($value)) {
            $strValue = $value;
        }
        else if (is_int($value)) {
            $strValue = date("Y.m.d H:i:s", $value);
        }
                                 
        return $strValue;        
    }
    
    /**
    * @desc Adott url-hez hozzáfûz további request paramétereket.
    * Az eljárással tetszõleges url módosítható.
    * Elsõdleges célja a backURL manipulálásnál van.
    */
    function addUrlQuery($url, $params) {
        $parsedUrl = parse_url($url); 
        parse_str($parsedUrl['query'], $queryParams);
        $queryParams = array_merge($queryParams, $params);
        $newQuery = NULL;
        foreach ($queryParams as $key => $value) {
            $newQuery .= ($newQuery ? '&' : '') . urlencode($key) . '='. urlencode($value); 
        }

        $newUrl = ''
            . (array_key_exists('scheme', $parsedUrl) ? $parsedUrl['scheme'] . '://' : '')
            . (array_key_exists('user', $parsedUrl) ? $parsedUrl['user'] : '')
            . (array_key_exists('pass', $parsedUrl) ? ':' . $parsedUrl['pass'] : '')
            . (array_key_exists('user', $parsedUrl) || array_key_exists('pass', $parsedUrl) ? '@' : '')
            . (array_key_exists('host', $parsedUrl) ? $parsedUrl['host'] : '')
            . (array_key_exists('port', $parsedUrl) ? ':' . $parsedUrl['port'] : '')
            . (array_key_exists('path', $parsedUrl) ? $parsedUrl['path'] : '')
            . ($newQuery ? '?' . $newQuery : '')
            . (array_key_exists('fragment', $parsedUrl) ? '#' . $parsedUrl['fragment'] : '');
        
        return $newUrl;
    }
    
    /**
    * @desc Vezérlés továbbítás adott címre (jellemzõen php kódra).
    * - Amennyiben az $url-ben nincs host, feltételezzük, hogy lokális
    * fájlról van szó, ezért az include() utasítással hivatkozunk a 
    * fájlra ahelyett, hogy redirect-álnánk rá. Ennek az az elõnye,
    * hogy a "hívó" által elõállított _REQUEST és _SESSION paraméterek
    * gond nélkül elérhetõek. A fájlnak, mint relatív elérésnek az 
    * include_path valamely eleme mentén elérhetõnek kell lennie.
    * - Ha az $url tartalmaz host megjelölést, akkor kliens oldali redirect
    * fog történni ("Location" header paraméter generálással)
    */
    function includeOrRedirect($url) {
        $parsedUrl = parse_url($url);
        if (!array_key_exists('host', $parsedUrl)) {
            include($url);   
        }
        else {
            header('Location: ' . $url);
        }
    }
    
    /**
    * @desc Konfigurációs paraméter kiolvasása simpleshop
    * vagy multishop környezet szerint kialakított
    * .config állományból
    * 
    * @param Array config az konfigurációs fájl tartalma
    * @param String paramName a keresett konfigurációs paraméter neve
    * @return string a paraméter értéke vagy null
    */
    public static function safeParam($request, $paramName) {
        return array_key_exists($paramName, $request) ? $request[$paramName] : null;
    }
    
}
