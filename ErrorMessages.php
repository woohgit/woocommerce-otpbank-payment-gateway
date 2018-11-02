<?php

/**
*
* A Banki felület által generálható és dokumentált hibaüzenet listája és azok 
* szöveges megfelelõi.
*
* Az összerendelést a $errorMessages global változó tartalmazza.
*
* @version 4.0
*
*/

global $errorMessages;

$errorMessages = array(
    '050' => 'A megadott kártyaadatok hibásak', 
    '051' => 'A megadott kártya lejárt', 
    '055' => 'A megadott kártya blokkolt.', 
    '056' => 'A megadott kártyaszám ismeretlen', 
    '057' => 'A megadott kártya elvesztett kártya', 
    '058' => 'A megadott kártyaszám érvénytelen', 
    '059' => 'A megadott kártya lejárt', 
    '069' => 'A megadott kártyaadatok hibásak', 
    '070' => 'A megadott kártyaszám érvénytelen, a BIN nem létezik', 
    '072' => 'A megadott kártya letiltott', 
    '074' => 'A megadott kártyaadatok hibásak', 
    '076' => 'A megadott kártyaadatok hibásak', 
    '082' => 'A megadott kártya terhelése a megadott összeggel nem lehetséges (jellemzõen vásárlási limittúllépés miatt)', 
    '089' => 'A megadott kártyaadatok hibásak, vagy nincs elég fedezet', 
    '097' => 'A megadott bankkártya nem aktív', 
    '200' => 'A megadott kártyaadatok hibásak', 
    '204' => 'A megadott kártya terhelése nem lehetséges', 
    '205' => 'Érvénytelen összegû vásárlás', 
    '206' => 'A megadott kártya az üzletági követelményeknek nem felel meg', 
    '901' => 'A megadott kártyaszám érvénytelen, a BIN nem létezik', 
    '902' => 'A megadott kártya lejárt', 
    '903' => 'A megadott bankkártya nem aktív', 
    '909' => 'A megadott kártya terhelése nem lehetséges', 
    'BASE24TIMEOUT' => 'A kártya autorizációs rendszer a beállított idõn belül nem küldött választ.', 
    'BINVISSZAUTASITVA' => 'A megadott kártyaszámmal a fizetési / regisztrálási tranzakció nem engedélyezett a bolt által igényelt bankkártya korlátozás miatt.', 
    'CDVKARTYA' => 'Hibás bankkártyaszám.',
    'DUPLAFIZETES' => 'A megadott tranzakció azonosító nem egyedi, azzal már létezik fizetési tranzakció.', 
    'DUPLALEZARAS' => 'A megadott tranzakció azonosítójú kétlépcsõs fizetés  már lezárásra került, vagy lezárása folyamatban van.', 
    'EXTREMDATUM' => 'Szélsõségesen távoli vagy érvénytelen dátum', 
    'FORMATUMBANKKARTYASZAM' => 'A megadott terhelendõ kártyaszám hibás formátumú', 
    'FORMATUMMENNYISEG' => 'A mennyiség mezõ formátuma nem megfelelõ.', 
    'FORMATUMTELEFONSZAM' => 'A megadott telefonszám nem értelmezhetõ telefonszámként.', 
    'FORMATUMTRANZAZON' => 'Hibás formátumú a megadott tranzakció azonosító.', 
    'HIANYZIKBANKKARTYASZAM' => 'A terhelendõ bankkártya száma nem került megadásra', 
    'HIANYZIKDATUM' => 'A bankkártya lejárat dátuma nem került megadásra', 
    'HIANYZIKLOGIKAI' => 'Kötelezõen kitöltendõ logikai értékû paraméter nem került megadása', 
    'HIANYZIKMENNYISEG' => 'Az összeg mezõ nem került kitöltésre', 
    'HIANYZIKNEV' => 'A név (bankkártya tulajdonos vagy vásárló neve) adat nem került kitöltésre', 
    'HIANYZIKNYELVKOD' => 'A nyelvkód nem került megadásra', 
    'HIANYZIKPOSAZONOSITO' => 'A Shop azonosító üres.', 
    'HIANYZIKSHOPKERESKEDOAZONOSITO' => 'AMEX kártyával történõ fizetés visszautasításra került', 
    'HIANYZIKSHOPPUBLIKUSKULCS' => 'A shop-hoz a Fizetõfelület rendszerében nem található a digitális aláírás ellenõrzéséhez szükséges publikus kulcs.', 
    'HIANYZIKTRANZAZON' => 'A tranzakció azonosító üres', 
    'HIBASCVCCVV' => 'A bankkártya ellenõrzõ (biztonsági) kódja nem vagy hibásan került megadásra', 
    'HIBASDATUM' => 'Hibás dátum formátum', 
    'HIBASDEVIZANEMKOD' => 'A devizanem kód hibás (nemlétezõ vagy nem támogatott devizanem szerepel benne).', 
    'HIBASKLIENSALAIRAS' => 'A kapott digitális aláírás nem hiteles, a tárolt publikus kulccsal ellenõrizve hibás ellenõrzõ összeg keletkezett.', 
    'HIBASLEJARATDATUMA' => 'Hibás formátumú a kártya lejárati dátuma.', 
    'HIBASNYELVKOD' => 'Hibás formátumú vagy hibás értékû nyelvkód érték (lásd ISO 639 nyelvkódokat)', 
    'HIBASPOSAZONOSITO' => 'Hibás formátumú a megadott shop azonosító (POS ID)', 
    'IDONTULIFELOLDAS' => 'A fizetés lezárása és a foglalás feloldása a türelmi idõszakún túl nem lehetséges.', 
    'IDONTULITERHELES' => 'A fizetés lezárása és a terhelés végrehajtása a türelmi idõszakún túl nem lehetséges.', 
    'INDULODATUMNAGYOBBAKTUALIS' => 'A szûrõ idõ intervallum alsó határa nem lehet késõbbi az aktuális idõpontnál.', 
    'KLIENSKODHIBA' => 'A Fizetõfelület tranzakcióiban a kliens kód csak "WEBSHOP” lehet', 
    'LEZARTKETLEPCSOSFIZETES' => 'A megadott tranzakció azonosítójú kétlépcsõs fizetésre lezárásra (elutasításra/feloldásra) került, vagy lezárása folyamatban van.', 
    'LETEZOFIZETESITRANZAKCIO' => 'A megadott tranzakció azonosítóval már létezik fizetési tranzakció.',
    'MENNYISEGPOZITIV' => 'A mennyiség mezõ tartalma csak pozitív lehet', 
    'NEMENGWEBSHOPFUNKCIO' => 'A funkció a hívó bolt számára nem engedélyezett.', 
    'NEMKETLEPCSOSFIZETES' => 'A megadott tranzakció azonosító nem kétlépcsõs fizetésre vonatkozik.', 
    'NEMLEZARASRAVAROTRANZAKCIO' => 'A megadott tranzakció nem kétlépcsõs vagy nem a fizetés  lezárására várakozik.', 
    'NEMLOGIKAI' => 'A megadott logikai érték érvénytelen', 
    'NINCSILYENWEBSHOP' => 'A megadott azonosítóval (POS ID) nem létezik ismert shop', 
    'RENDSZERHIBA' => 'Súlyos Fizetõfelület-oldali hiba a folyamat feldolgozása közben. A folyamat beállítási vagy más belsõ probléma miatt nem tudott helyesen lefutni.', 
    'WEBSHOPIPNEMENGEDELYEZETT' => 'Az IP címrõl a Bank nem fogad el fizetõfelület tranzakció kéréseket', 
    'ZARODATUMNAGYOBBINDULO' => 'A szûrõ idõ intervallum felsõ határa nem lehet késõbbi az alsó határ idõpontnál.', 
    'NEMLETEZOWEBSHOPUGYFELAZON' => 'A megadott ügyfél regisztrációs azonosító nem létezik vagy deregisztrálásra került.',
    'NEMJOVAIRHATOTRANZAKCIO' => 'A megadott adatokkal sikeres fizetési tranzakció nem található.',
    'JOVAIRANDOOSSZEGNAGYOBBMINTTERHELT' => 'A megadott jóváírandó összeg nagyobb mint az eredeti fizetési tranzakció össszegének és az eddigi jóváírások összegének a különbsége.',
    'TERHELENDOOSSZEGNAGYOBBMINTZAROLT'  => 'A megadott terhelendõ összeg nagyobb mint az eredeti fizetési tranzakció össszege.',
    'FELOLDANDOOSSZEGNEMUGYANAZMINTZAROLT'  => 'A megadott feloldandó összeg nem egyenlõ az eredeti fizetési tranzakció össszegével.',

);

function getMessageText($messageCode)  {
    global $errorMessages;
    return $errorMessages[$messageCode];
}
