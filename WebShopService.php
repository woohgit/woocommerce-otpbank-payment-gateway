<?php

require_once 'WResponse.php';
require_once 'RequestUtils.php';
require_once 'WebShopXmlUtils.php';
require_once 'SignatureUtils.php';
require_once 'SoapUtils.php';
require_once 'DefineConst.php';
require_once 'ConfigUtils.php';

class WebShopService {

    var $soapClient;

    var $lastInputXml = NULL;

    var $lastOutputXml = NULL;

    var $operationLogNames = array(
        'fizetesiTranzakcio' => 'fizetesiTranzakcio',
        'tranzakcioStatuszLekerdezes' => 'tranzakcioStatuszLekerdezes'
    );

    function WebShopService() {
        $this->soapClient = SoapUtils::createSoapClient();
    }

    /**
     * @desc A banki felület Ping szolgáltatásának meghívása.
     * Mivel tranzakció indítás nem történik, a sikeres ping
     * esetén sem garantált az, hogy az egyes fizetési tranzakciók
     * sikeresen el is indíthatók -  csupán az biztos, hogy a
     * hálózati architektúrán keresztül sikeresen elérhetõ a
     * banki felület.
     *
     * Digitális aláírás nem képzödik.
     *
     * @return boolean true sikeres ping-etés esetén, egyébként false.
     */
    function ping() {
        $result = SoapUtils::ping($this->soapClient);
        return $result;
    }

    /**
     * Háromszereplõs fizetési folyamat (WEBSHOPFIZETES) szinkron indítása.
     *
     * @param string $posId
     *        webshop azonosító
     * @param string $tranzakcioAzonosito
     *        fizetési tranzakció azonosító
     * @param mixed $osszeg
     *        Fizetendö összeg, (num, max. 13+2), opcionális tizedesponttal.
     *        Nulla is lehet, ha a regisztraltUgyfelId paraméter ki van
     *        töltve, és az ugyfelRegisztracioKell értéke igaz. Így kell
     *        ugyanis jelezni azt, hogy nem tényleges vásárlási tranzakciót
     *        kell indítani, hanem egy ügyfél regisztrálást, vagyis az
     *        ügyfél kártyaadatainak bekérést és eltárolását a banki
     *        oldalon.
     * @param string $devizanem
     *            fizetendö devizanem
     * @param string $nyelvkod
     *            a megjelenítendö vevö oldali felület nyelve
     * @param mixed $nevKell
     *            a megjelenítendö vevö oldali felületen be kell kérni a vevö
     *            nevét
     * @param mixed $orszagKell
     *            a megjelenítendö vevö oldali felületen be kell kérni a vevö
     *            címének "ország részét"
     * @param mixed $megyeKell
     *            a megjelenítendö vevö oldali felületen be kell kérni a vevö
     *            címének "megye részét"
     * @param mixed $telepulesKell
     *            a megjelenítendö vevö oldali felületen be kell kérni a vevö
     *            címének "település részét"
     * @param mixed $iranyitoszamKell
     *            a megjelenítendö vevö oldali felületen be kell kérni a vevö
     *            címének "irányítószám részét"
     * @param mixed $utcaHazszamKell
     *            a megjelenítendö vevö oldali felületen be kell kérni a vevö
     *            címének "utca/házszám részét"
     * @param mixed $mailCimKell
     *            a megjelenítendö vevö oldali felületen be kellûkérni a vevö
     *            e-mail címét
     * @param mixed $kozlemenyKell
     *            a megjelenítendö vevö oldali felületen fel kell kínálni a
     *            közlemény megadásának lehetöségét
     * @param mixed $vevoVisszaigazolasKell
     *            a tranzakció eredményét a vevö oldalon meg kell jeleníteni
     *            (azaz nem a backURL-re kell irányítani)
     * @param mixed $ugyfelRegisztracioKell
     *            ha a regisztraltUgyfelId értéke nem üres, akkor megadja, hogy
     *            a megadott azonosító újonnan regisztrálandó-e, vagy már
     *            regisztrálásra került az OTP Internetes Fizetõ felületén.
     *            Elõbbi esetben a kliens oldali böngészõben olyan fizetõ oldal
     *            fog megjelenni, melyen meg kell adni az azonosítóhoz tartozó
     *            jelszót, illetve a kártyaadatokat. Utóbbi esetben csak az
     *            azonosítóhoz tartozó jelszó kerül beolvasásra az értesítési
     *            címen kívül. Ha a regisztraltUgyfelId értéke üres, a pamaréter
     *            értéke nem kerül felhasználásra.
     * @param string $regisztraltUgyfelId
     *            az OTP fizetõfelületen regisztrálandó vagy regisztrált ügyfél
     *            azonosító kódja.
     * @param string $shopMegjegyzes
     *            a webshop megjegyzése a tranzakcióhoz a vevö részére
     * @param string $backURL
     *            a tranzakció végrehajtása után erre az internet címre kell
     *            irányítani a vevö oldalon az ügyfelet (ha a
     *            vevoVisszaigazolasKell hamis)
     * @param string $zsebAzonosito
     * 			  a cafeteria kártya zseb azonosítója.
     * @param mixed $ketlepcsosFizetes
     * 			  megadja, hogy kétlépcsõs fizetés indítandó-e.
     *            True érték esetén a fizetési tranzakció kétlépcsõs lesz,
     *            azaz a terhelendõ összeg csupán zárolásra kerül,
     *            s úgy is marad a bolt által indított lezáró tranzakció
     *            indításáig avagy a zárolás elévüléséig.
     *            Az alapértelmezett (üres) érték a Bank oldalon rögzített
     *            alapértelmezett módot jelöli.
     *
     * @return WResponse a tranzakció válaszát reprezentáló value object.
     *         Sikeres végrehajtás esetén a válasz adatokat WebShopFizetesAdatok
     *         objektum reprezentálja.
     *         Kommunikációs hiba esetén a finished flag false értékû lesz!
     */
    function fizetesiTranzakcio(
            $posId,
            $azonosito,
            $osszeg,
            $devizanem,
            $nyelvkod,
            $nevKell,
            $orszagKell,
            $megyeKell,
            $telepulesKell,
            $iranyitoszamKell,
            $utcaHazszamKell,
            $mailCimKell,
            $kozlemenyKell,
            $vevoVisszaigazolasKell,
            $ugyfelRegisztracioKell,
            $regisztraltUgyfelId,
            $shopMegjegyzes,
            $backURL,
            $zsebAzonosito,
            $ketlepcsosFizetes,
            $key_original) {


        $dom = WebShopXmlUtils::getRequestSkeleton(WF_HAROMSZEREPLOSFIZETESINDITAS, $variables);

        // default értékek feldolgozása
        if (is_null($devizanem) || (trim($devizanem) == "")) {
            $devizanem = DEFAULT_DEVIZANEM;
        }

        /* paraméterek beillesztése */
        WebShopXmlUtils::addParameter($dom, $variables, CLIENTCODE, CLIENTCODE_VALUE);
        WebShopXmlUtils::addParameter($dom, $variables, POSID, $posId);
        WebShopXmlUtils::addParameter($dom, $variables, TRANSACTIONID, $azonosito);
        WebShopXmlUtils::addParameter($dom, $variables, AMOUNT, $osszeg);
        WebShopXmlUtils::addParameter($dom, $variables, EXCHANGE, $devizanem);
        WebShopXmlUtils::addParameter($dom, $variables, LANGUAGECODE, $nyelvkod);

        WebShopXmlUtils::addParameter($dom, $variables, NAMENEEDED, RequestUtils::booleanToString($nevKell));
        WebShopXmlUtils::addParameter($dom, $variables, COUNTRYNEEDED, RequestUtils::booleanToString($orszagKell));
        WebShopXmlUtils::addParameter($dom, $variables, COUNTYNEEDED, RequestUtils::booleanToString($megyeKell));
        WebShopXmlUtils::addParameter($dom, $variables, SETTLEMENTNEEDED, RequestUtils::booleanToString($telepulesKell));
        WebShopXmlUtils::addParameter($dom, $variables, ZIPCODENEEDED, RequestUtils::booleanToString($iranyitoszamKell));
        WebShopXmlUtils::addParameter($dom, $variables, STREETNEEDED, RequestUtils::booleanToString($utcaHazszamKell));
        WebShopXmlUtils::addParameter($dom, $variables, MAILADDRESSNEEDED, RequestUtils::booleanToString($mailCimKell));
        WebShopXmlUtils::addParameter($dom, $variables, NARRATIONNEEDED, RequestUtils::booleanToString($kozlemenyKell));
        WebShopXmlUtils::addParameter($dom, $variables, CONSUMERRECEIPTNEEDED, RequestUtils::booleanToString($vevoVisszaigazolasKell));

        WebShopXmlUtils::addParameter($dom, $variables, BACKURL, $backURL);

        WebShopXmlUtils::addParameter($dom, $variables, SHOPCOMMENT, $shopMegjegyzes);

        WebShopXmlUtils::addParameter($dom, $variables, CONSUMERREGISTRATIONNEEDED, $ugyfelRegisztracioKell);
        WebShopXmlUtils::addParameter($dom, $variables, CONSUMERREGISTRATIONID, $regisztraltUgyfelId);

        WebShopXmlUtils::addParameter($dom, $variables, TWOSTAGED, RequestUtils::booleanToString($ketlepcsosFizetes, NULL));
        WebShopXmlUtils::addParameter($dom, $variables, CARDPOCKETID, $zsebAzonosito);

        /* aláírás kiszámítása és paraméterként beszúrása */
        $signatureFields = array(0 =>
            $posId, $azonosito, $osszeg, $devizanem, $regisztraltUgyfelId);
        $signatureText = SignatureUtils::getSignatureText($signatureFields);

        $pkcs8PrivateKey = SignatureUtils::loadPrivateKey($key_original);
        $signature = SignatureUtils::generateSignature($signatureText, $pkcs8PrivateKey);

        $attrName = null;
		$attrValue = null;

		if (version_compare(PHP_VERSION, '5.4.8', '>=')) {
			$attrName = 'algorithm';
			$attrValue = 'SHA512';
		}

        WebShopXmlUtils::addParameter($dom, $variables, CLIENTSIGNATURE, $signature, $attrName, $attrValue);

        $this->lastInputXml = WebShopXmlUtils::xmlToString($dom);

        /* Tranzakció adatainak naplózása egy külön fájlba */

        /* A tranzakció indítása */
        $startTime = time();
        $workflowState = SoapUtils::startWorkflowSynch(WF_HAROMSZEREPLOSFIZETESINDITAS, $this->lastInputXml, $this->soapClient);

        if (!is_null($workflowState)) {
            $response = new WResponse(WF_HAROMSZEREPLOSFIZETES, $workflowState);
        }
        else {
            // A tranzakció megszakadt, a banki felület válaszát nem
            // tudta a kliens fogadni
            $poll = true;
            $resendDelay = 20;
            do {
                $tranzAdatok = $this->tranzakcioPoll($posId, $azonosito, $startTime, $key_original);
                if ($tranzAdatok === false) {
                    // nem sikerült a lekérdezés, újrapróbálkozunk
                    $poll = true;
                }
                else {
                    if ($tranzAdatok->isFizetesFeldolgozasAlatt()) {
                        // a tranzakció feldolgozás alatt van
                        // mindenképp érdemes kicsit várni, és újra pollozni
                    }
                    else {
                        // a tranzakció feldolgozása befejezõdött
                        // (lehet sikeres vagy sikertelen az eredmény)
                        $poll = false;
                        $response = new WResponse(WF_HAROMSZEREPLOSFIZETES, null);
                        // a folyamat válaszának naplózása
                        $response->loadAnswerModel($tranzAdatok, $tranzAdatok->isSuccessful(), $tranzAdatok->getPosValaszkod());
                        return $response;
                    }
                }
                $retryCount++;
                sleep($resendDelay);
            } while ($poll && ($startTime + 660 > time()));
            // pollozunk, amíg van értelme, de legfeljebb 11 percig!

        }

        // a folyamat válaszának naplózása
        if ($response->isFinished()) {
            $responseDom = $response->getResponseDOM();
            $this->lastOutputXml = WebShopXmlUtils::xmlToString($responseDom);
        }
        else {
        }

        return $response;
    }

    /**
     * WEBSHOPTRANZAKCIOLEKERDEZES folyamat szinkron indítása.
     *
     * @param string $posId webshop azonosító
     * @param string $azonosito lekérdezendõ tranzakció azonosító
     * @param mixed $maxRekordSzam maximális rekordszám (int / string)
     * @param mixed $idoszakEleje lekérdezendõ idõszak eleje
     *        ÉÉÉÉ.HH.NN ÓÓ:PP:MM alakú string érték vagy int timestamp
     * @param mixed $idoszakEleje lekérdezendõ idõszak vége
     *        ÉÉÉÉ.HH.NN ÓÓ:PP:MM alakú string érték vagy int timestamp
     *
     * @return WResponse a tranzakció válaszát reprezentáló value object.
     *         Sikeres végrehajtás esetén a válasz adatokat WebShopAdatokLista
     *         objektum reprezentálja.
     *         Kommunikációs hiba esetén a finished flag false értékû lesz!
     */
    function tranzakcioStatuszLekerdezes(
            $posId,
            $azonosito,
            $maxRekordSzam,
            $idoszakEleje,
            $idoszakVege,
            $key_original) {


        $dom = WebShopXmlUtils::getRequestSkeleton(WF_TRANZAKCIOSTATUSZ, $variables);

        $idoszakEleje = RequestUtils::dateToString($idoszakEleje);
        $idoszakVege = RequestUtils::dateToString($idoszakVege);

        /* paraméterek beillesztése */
        WebShopXmlUtils::addParameter($dom, $variables, CLIENTCODE, CLIENTCODE_VALUE);
        WebShopXmlUtils::addParameter($dom, $variables, POSID, $posId);
        WebShopXmlUtils::addParameter($dom, $variables, TRANSACTIONID, $azonosito);
        WebShopXmlUtils::addParameter($dom, $variables, QUERYMAXRECORDS, $maxRekordSzam);
        WebShopXmlUtils::addParameter($dom, $variables, QUERYSTARTDATE, $idoszakEleje);
        WebShopXmlUtils::addParameter($dom, $variables, QUERYENDDATE, $idoszakVege);

        /* aláírás kiszámítása és paraméterként beszúrása */
        $signatureFields = array(0 =>
            $posId, $azonosito,
            $maxRekordSzam, $idoszakEleje, $idoszakVege );
        $signatureText = SignatureUtils::getSignatureText($signatureFields);

        $pkcs8PrivateKey = SignatureUtils::loadPrivateKey($key_original);
        $signature = SignatureUtils::generateSignature($signatureText, $pkcs8PrivateKey);

        $attrName = null;
		$attrValue = null;

		if (version_compare(PHP_VERSION, '5.4.8', '>=')) {
			$attrName = 'algorithm';
			$attrValue = 'SHA512';
		}

        WebShopXmlUtils::addParameter($dom, $variables, CLIENTSIGNATURE, $signature, $attrName, $attrValue);

        $this->lastInputXml = WebShopXmlUtils::xmlToString($dom);

        /* a folyamat indítása */
        $workflowState = SoapUtils::startWorkflowSynch(WF_TRANZAKCIOSTATUSZ, $this->lastInputXml, $this->soapClient);
        $response = new WResponse(WF_TRANZAKCIOSTATUSZ, $workflowState);

        /* a folyamat válaszának naplózása */
        if ($response->isFinished()) {

            $responseDom = $response->getResponseDOM();
            $this->lastOutputXml = WebShopXmlUtils::xmlToString($responseDom);
        }
        else {
        }

        return $response;
    }

    /**
     * WEBSHOPTRANZAKCIOLEKERDEZES folyamat szinkron indítása pollozás céljából.
     * A bank nem javasolja, hogy pollozásos technikával történjen a fizetési
     * tranzakciók eredményének lekérdezése - mindazonáltal kommunikációs vagy
     * egyéb hiba esetén ez az egyetlen módja annak, hogy a tranzakció válaszát
     * utólag le lehessen kérdezni.
     *
     * @param string $posId webshop azonosító
     * @param string $azonosito lekérdezendõ tranzakció azonosító
     * @param int $inditas a tranzakció indítása az indító kliens órája szerint
     *                     (a lekérdezés +-24 órára fog korlátozódni)
     *
     * @return mixed Sikeres lekérdezés és létezõ tranzakció esetén
     *               a vonatkozó WebShopFizetesAdatok. A tranzakció állapotát
     *               ez az objektum fogja tartalmazni - ami utalhat például
     *               vevõ oldali input várakozásra vagy feldolgozott státuszra.
     *               FALSE hibás lekérdezés esetén. (Pl. nem létezik tranzakció)
     */
    function tranzakcioPoll($posId, $azonosito,  $inditas, $key_original) {

        $maxRekordSzam = "1";
        $idoszakEleje = $inditas - 60*60*24;
        $idoszakVege = $inditas + 60*60*24;

        $tranzAdatok = false;
        $response = $this->tranzakcioStatuszLekerdezes($posId, $azonosito, $maxRekordSzam, $idoszakEleje, $idoszakVege, $key_original);
        if ($response) {
            $answer = $response->getAnswer();
            if ($response->isSuccessful()
                    && $response->getAnswer()
                    && count($answer->getWebShopFizetesAdatok()) > 0) {

                // Sikerült lekérdezni az adott tranzakció adatát
                $fizetesAdatok = $answer->getWebShopFizetesAdatok();
                $tranzAdatok = reset($fizetesAdatok);
            }
        }
        return $tranzAdatok;
    }
}

?>
