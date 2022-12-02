# Online fájlkezelő

## Specifikáció:

A feladat egy webes alkalmazás, amibe felhasználók fájlokat tölthetnek fel és le, továbbá szöveges fájlokat hozhatnak létre és szerkeszthetnek is.

Az alkalmazás felhasználónév/jelszót igénylő bejelentkezéssel védett. Felhasználói fiókot bárki hozhat létre regisztrációval (név, felhasználónév, email cím, jelszó megadással). A felhasználónév és az email cím egyedi kell hogy legyen. Minden felhasználó csak a saját maga által létrehozott vagy feltöltött fájlokat láthatja, módosíthatja, vagy töltheti le.

Bejelentkezés után legyen látható egy fájl lista, amin szerepel a fájlok neve, létrehozási dátuma, mérete.

A lista alapértelmezetten legyen a fájlok nevére abc-be rendezve, és mutassa minden elem módosítás dátumát. A listát lehessen módosítás dátumára is rendezni. A listán megjelenő találatok száma legyen korlátozva 20-ra. Legyessen gombnymással lapozni a következő 20 találatra.
A listán egy kereső mezőbe beírt szöveg alapján lehessen keresni az összes fájl nevében, szótöredék szerinti egyezéssel.

A listáról kiindulva lehessen új fájlokat feltölteni, szöveges fájlt létrehozni, meglévőket szerkeszteni, törölni. Fájlok neve nem kell hogy egyedi legyen. Több fájlnak is lehet egyszerre ugyanaz a neve.

Amikor új szöveges fájlt akarok létrehozni, akkor vigyen el egy üres űrlapra, ahol megadhatom a nevét és a tartalmát. Sikeres mentéskor térjen vissza a listára.
A listán a szöveges fájlok mellett legyen egy szerkeszt gomb vagy link, amire kattintva elvisz az űrlapra, ahol a tartalmát és a nevét szerkeszthetem. Mentéskor térjen vissza a listára.

A listán minden elem mellett legyen egy töröl gomb vagy link amivel törölhetem az adott bejegyzést.

A felhasználók tudjanak fájlokat “átküldeni” egymásnak. Ehhez meg kell adniuk a címzett felhasználónevét, és kiválasztani egy vagy több fájlt. Ezekről a fájlokról a másik felhasználó másolatot kap (nem megosztás). Az ilyen módott kapott fájlokat is ugyanúgy lehet látni és kezelni, mint a saját maga által létrehozottakat. A másolatokról láthatónak kell lennie, hogy ki küldte át. A fogadó felhasználónak a saját email címére email értesítést kell kapnia az átküldött fájl(ok)ról, amiben szerepel a fájlok neve, és a küldő felhasználó neve.

Bejelentkezés után a felhasználónak legyen lehetősége az felhasználó nevének és jelszavának megváltoztatására. Legyen egy kilépés gomb ami mindig látszik valahol az oldalon, függetlenül attól, hogy melyik felületen vagyok.


## Technikai követelmények:
1. Az alkalmazást PHP/MySQL platformon kell megvalósítani, szigorúan objektum-orientáltan, 100%-ig saját készítésű kóddal.
2. Az alkalmazásnak egyetlen belépési pontja lehet (index.php), minimális mennyiségű kóddal (néhány sor). A többi fájl csak osztálydefiníciót tartalmazhat, közvetlenül futtatható kódot nem!
3. Minden PHP osztály külön php fájlban legyen.
4. Minden osztályt egy központi autoload-on keresztül kell betölteni. Az alkalmazás kódja (a belépési pontot leszámítva) include-ot vagy require-t sehol máshol nem tartalmazhat!
5. Illetéktelen felhasználók akkor se tölthessenek le fájlokat ha hozzájutnak egy fájl url-jéhez.
6. Minden, a felhasználótól származó adat helyessége legyen ellenőrizve. Hibás adat esetén az alkalmazás jelenítsen meg érthető hibaüzeneteket.
7. Az alkalmazás rendelkezzen modell réteggel, ami a felülettől megfelelő módon van elválasztva.