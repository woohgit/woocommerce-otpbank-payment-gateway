<?php

/**
*
* A WebShop PHP kliens és a SimpleShop PHP komponensei által használatos
* konstans szövegek, melyek a banki kommunikációhoz kapcsolódnak:
* - banki tranzakciók kódjai
* - tranzakció indításhoz kötödõ xml attribútum nevek
* - használt konfigurációs állományok (.config) kulcsai
* 
* @version 4.0
*
*/

/* A bolt oldali WEB felület által használt karakter encoding */
if (!defined('WS_CUSTOMERPAGE_CHAR_ENCODING')) define('WS_CUSTOMERPAGE_CHAR_ENCODING', 'ISO-8859-2');

/* A bank oldali felület felé használt karakter encoding */
if (!defined('WF_INPUTXML_ENCODING')) define('WF_INPUTXML_ENCODING', 'UTF-8');

/* A tranzakciós naplófájl dátum formátuma */
if (!defined('LOG_DATE_FORMAT')) define ('LOG_DATE_FORMAT', 'Y.m.d. H:i:s');

/* Az indítható tranzakciók nevei. */
define('WF_TRANZAZONGENERALAS', 'WEBSHOPTRANZAZONGENERALAS');
define('WF_HAROMSZEREPLOSFIZETES', 'WEBSHOPFIZETES');
define('WF_HAROMSZEREPLOSFIZETESINDITAS', 'WEBSHOPFIZETESINDITAS');
define('WF_KETSZEREPLOSFIZETES', 'WEBSHOPFIZETESKETSZEREPLOS');
define('WF_TRANZAKCIOSTATUSZ', 'WEBSHOPTRANZAKCIOLEKERDEZES');
define('WF_KETLEPCSOSFIZETESLEZARAS', 'WEBSHOPFIZETESLEZARAS');
define('WF_FIZETESJOVAIRAS', 'WEBSHOPFIZETESJOVAIRAS');
define('WF_KULCSLEKERDEZES', 'WEBSHOPKULCSLEKERDEZES');

/* A válasz xml-ben mejelenõ sikeres végrehajtásra utaló üzenetkód. */ 
define ('WF_SUCCESS_TEXTS', 'SIKER');

/* Alap xml elemek. */
define('TEMPLATENAME_TAGNAME', 'TemplateName');
define('VARIABLES_TAGNAME', 'Variables');

define('CLIENTCODE', 'isClientCode');
define('CLIENTCODE_VALUE', 'WEBSHOP');

/* Input xml változó nevek. */
define('AMOUNT', 'isAmount');
define('BACKURL', 'isBackURL');
define('CONSUMERRECEIPTNEEDED', 'isConsumerReceiptNeeded');
define('COUNTRYNEEDED', 'isCountryNeeded');
define('COUNTYNEEDED', 'isCountyNeeded');
define('EXCHANGE', 'isExchange');
define('LANGUAGECODE', 'isLanguageCode');
define('MAILADDRESSNEEDED', 'isMailAddressNeeded');
define('NAMENEEDED', 'isNameNeeded');
define('NARRATIONNEEDED', 'isNarrationNeeded');
define('POSID', 'isPOSID');
define('SETTLEMENTNEEDED', 'isSettlementNeeded');
define('SHOPCOMMENT', 'isShopComment');
define('STREETNEEDED', 'isStreetNeeded');
define('TRANSACTIONID', 'isTransactionID');
define('ZIPCODENEEDED', 'isZipcodeNeeded');
define('CLIENTSIGNATURE', 'isClientSignature');
define('CONSUMERREGISTRATIONNEEDED', 'isConsumerRegistrationNeeded');
define('CONSUMERREGISTRATIONID', 'isConsumerRegistrationID');
define('TWOSTAGED', 'isTwoStaged');
define('CARDPOCKETID', 'isCardPocketId');
define('MUVELET', 'isMuvelet');

/* Input xml változó nevek - kétszereplös fizetéshez. */
define('CARDNUMBER', 'isCardNumber');
define('CVCCVV', 'isCVCCVV');
define('EXPIRATIONDATE', 'isExpirationDate');
define('NAME', 'isName');
define('FULLADDRESS', 'isFullAddress');
define('IPADDRESS', 'isIPAddress');
define('MAILADDRESS', 'isMailAddress');
define('TELEPHONE', 'isTelephone');

/* Input xml változó nevek - tranzakciók lekérdezéséhez. */
define('QUERYMAXRECORDS', 'isMaxRecords');
define('QUERYSTARTDATE', 'isStartDate');
define('QUERYENDDATE', 'isEndDate');

/* Input xml változó nevek - kétlépcsõs fizetés lezárásához. */
define('APPROVED', 'isApproval');

/* Az input xml-ben megjelenõ két fajta logikai érték szöveges formája. */ 
define('REQUEST_TRUE_CONST', 'TRUE');
define('REQUEST_FALSE_CONST', 'FALSE');

/* Alapértelmezett devizanem */
define('DEFAULT_DEVIZANEM',  'HUF');

/* WebShopService konfigurációs fájl kulcs értékek */
define('PROPERTY_PRIVATEKEYFILE', 'otp.webshop.PRIVATE_KEY_FILE');
define('PROPERTY_OTPMWSERVERURL',  'otp.webshop.OTPMW_SERVER_URL');

define('PROPERTY_HTTPSPROXYHOST',  'otp.webshop.client.HTTPS_PROXYHOST');
define('PROPERTY_HTTPSPROXYPORT',  'otp.webshop.client.HTTPS_PROXYPORT');
define('PROPERTY_HTTPSPROXYUSER',  'otp.webshop.client.HTTPS_PROXYUSER');
define('PROPERTY_HTTPSPROXYPASSWORD',  'otp.webshop.client.HTTPS_PROXYPASSWORD');

define('PROPERTY_TRANSACTIONLOGDIR',  'otp.webshop.TRANSACTION_LOG_DIR');
define('PROPERTY_TRANSACTIONLOG_SUCCESS_DIR',  'otp.webshop.transaction_log_dir.SUCCESS_DIR');
define('PROPERTY_TRANSACTIONLOG_FAILED_DIR',  'otp.webshop.transaction_log_dir.FAILED_DIR');

define('PROPERTY_OPENSSL_EXECUTIONPATH', 'otp.webshop.OPENSSL_EXECUTEPATH');
define('PROPERTY_OPENSSL_EXECUTIONPARAMS', 'otp.webshop.OPENSSL_EXECUTEPARAMS');

/* Banki kommunikáció: Újraküldések maximális száma */
define('RESENDCOUNT',  10);

/* Banki kommunikáció: Újraküldések közti késleltetési idõ */
define('RESENDDELAY',  1);

/* Banki kommunikáció: Újraküldések csak akkor lehetségesek, ha a lenti szöveg
   szerepel az elutasításhoz tartozó kivételben. */
define('RESEND_ERRORPATTERN',  "Maximum workflow number is reached");
  
?>