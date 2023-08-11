# Állásinterjú feladat

Tisztelt Oktatási Hivatal!

Röviden írok néhány sort arról, hogy mit miért így csináltam.

## Backend

#### Form Request
A request valdiációnál én csak az adatok meglétét és típusát ellenőriztem a többi dolgot pedig a service rétegre hagytam, természetesen itt is lehetett volna ellenőrzni pl.: hogy minden vizsgatrágy eredménye 20% felett van-e, de én a service rétegben talán átláthatóbb.

#### Config
Mivel a feladat kifejezetten csak 2 szak érettségi követleményét kérte így az a megoldás volt számomra a legkézenfekvőbb, hogy egy configba lementem.

#### DTO
A DTO-k használatát 2 dolog miatt tartotam szükségesnek az egyik, hogy jobban preferálom az angol változó neveket a másik pedig, hogy mivel az adat több helyről jöhet(frontend vagy akár command) és a POPO-val szemben a DTO tartalmazhat függvényeket is, így lehet biztosítnai, hogy a Service réteg mindig ugyan azt az objektumot kapja

## Frontend
Eddigi munkáim során még nem volt alkalmam Vue-val dolgozni így egy gyors dokumentáció átolvasás után arra jutottam, hogy egy ilyen egyszerűbb applikációt érdemesebb az Options API-val megvalósítani. Természetesen biztos vagyok benne, hogy a megoldás nem tökéletes, de amit tudtam megtettem.

## Összegzés
A kód írásakor figyeltem rá, hogy az eredeti input nevekkel lehessem tesztelni, viszont személy szerint én jobban preferálom az angol változó neveket ezért is van backend oldal egy adat transzformáció.