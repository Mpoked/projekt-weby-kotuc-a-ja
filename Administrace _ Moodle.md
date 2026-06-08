<!-- Converted from Administrace _ Moodle.pdf — 16 pages -->



---

## Page 1

# Administrace

Stránky: OAUH Moodle Vytiskl(a): Marek Pokorný
Kurz: Webové aplikace - materiály Datum: pondělí, 8. června 2026, 17.43
Kniha: Administrace

---

## Page 2

# Obsah

## 1. Úvod

## 2. Knihovna Ion Auth
## 2.1. Stažení a instalace
## 2.2. Konfigurace knihovny
## 2.3. Sessions

## 3. Přihlášení a odhlášení
## 3.1. Přihlášení
## 3.2. Zpracování přihlášení
## 3.3. Odhlášení
## 3.4. Dostupnost rout

## 4. Registrace
## 4.1. Formulář
## 4.2. Validace
## 4.3. Zpracování formuláře

---

## Page 3

# 1. Úvod

Administrací se rozumí ta část webu, která je dostupná až po přihlášení. Její routy musí být dostupné pouze přihlášeným uživatelům (či dokonce
pouze těm uživatelům, kteří mají odpovídající práva k dané routě).

Musíme tedy vyřešit jednat ochranu těchto rout, musíme zajisti možnost přihlášení a odhlášení a všech dalších věcí, které s tím souvisí
(registrace, zapomenuté heslo)

---

## Page 4

# 2. Knihovna Ion Auth

Tato knihovna je výbornou knihovnou, která nám dokáže zajistit věci související s přihlášením, odhlášením registrací atd. Nebudeme muset řešit
takové věci, jako je hashování hesla, vytváření tabulek pro uživatele apod., to vše je už součástí této knihovny.

---

## Page 5

# 2.1. Stažení a instalace

## U projektu už předpokládáme nainstalovaný a zprovozněný CodeIgniter.

## Knihovnu nainstalujeme s využitím Composeru. Celý návod na instalaci je v dokumentaci knihovny na Githubu (link). Samotný instalační návod
## je v souboru install.md.

## Nejprve pomocí Composeru musíme povolit instalaci z balíčků, které jsou v development modu, následně přidáme repozitář s ion authem a
## konečně v posledním kroku nainstalujeme Ion auth - viz návod, tří řádky s příkazy do Composeru.

## Při instalaci z Githubu po nás může Github požadovat token, který si nastavíme na Githubu. V profilu na strnkách Githubu dáme Settings, pak
## Developer settings, pak Personal Access Tokens a vybereme Classic token. Vygenerujeme si classic token a jeho kód pak vložíme do terminálu
## ve Visual studiu. Připomínám, že to funguje ve stylu Linuxu, takže se nám nezobrazí ani obsah toho tokenu, ani žádné hvězdičky nebo tečky, ale
## prostě se po vložení kódu nezobrazí vůbec nic. Že jsme to vložili, poznáme po odeslání, kdy se nám Ion Auth nainstaluje (ve složce vendor
## přibude složka benedmunds).

## Na závěr nastavíme autoloading (viz návod v dokumentaci).

## Nyní se vrhneme na databázi. Ion auth obsahuje tabulky, které budeme používat pro naši přihlášení, takže je musíme do projektu přidat.
## Nejjednodušší bude si v knihovně ion auth (ve složce vendor) najít složku sql, kde jsou tři soubory s příponou sql. My si vybereme ten , který se
## jmenuje ion_auth.sql (pokud používáme databázi MySQl nebo MariaDB, další dva jsou pro PostgreSQL nebo pro MSSQL) a kó přes
## phpmyadmina vložíme do naší databáze a přidáme případné prefixy. Celkově se vytvoří 4 tabulky.

---

## Page 6

## nešahá (abychom neměli problém při případné aktualizaci přes Github).

## IonAuth, která bude potomkem třídy z vendoru. Kód bude vypadat asi takto:

<?php
namespace Config;

class IonAuth extends \IonAuth\Config\IonAuth
{
// public $siteTitle = 'Example.com'; // Site Title, example.com
// public $adminEmail = 'admin@example.com'; // Admin Email, admin@example.com
// public $emailTemplates = 'App\\Views\\auth\\email\\';
// ...
}

## vendoru.

## třeba minimální délka hesla.

---

# 2.2. Konfigurace knihovny

## Veškeré nastavení konfigurací je v konfiguračním souboru knihovny. Ten se nachází v instalační složce knihovny (tj. ve vendoru), kam se většinou

## Proto si vytvoříme vlastní konfigurační soubor (ve složce app/config), který pojmenujeme třeba IonAuth.php. V tomto souboru vytvoříme třídu

## V jednotlivých proměnných nastavíme hlavně ty údaje, které chceme mít jinak než v defaultním konfiguračním souboru, který máme ve

## Důležitá je především proměnná $identity, kde nastavíme název sloupce z tabulky user, pomocí kterého se budeme přihlašovat. Dál pak také

---

## Page 7

ve Firefoxu odhlášení).

# Zprovoznění sessions

$this->session = \Config\Services::session();

informace.

# Konfigurace sessions

proměnné expiration (čas v sekundách), tak nás to odhlásí.

---

## 2.3. Sessions

Session jsou nutnou podmínkou pro to, abychom mohli využívat autentifikaci. Sessions jsou vlastně maličké soubory, které se ukládají na server
a jsou v nich uložené informace o uživateli. Každá session je unikátní pro IP adresu a prohlížeč (takže např. v Chromu můžeme být přihlášení a

Do konstruktoru BaseControlleru musíme vložit kód, kterým sessions spustíme. Samozřejmě je spustíme tak, aby byly pak dostupné pořád.

V tomto okamžiku budeme mít sessions dostupné v každém controlleru a tudíž při každé akci se do nich budou moct ukládat potřebné

Session se konfigurují v souboru Session.php, který najdeme v app/config. Zde bych doporučoval věnovat pozornost způsobu ukládání
sessions (proměnná driver), defaultní volbou je ukládání do souboru. Pokud tam tuto hodnotu necháme, musíme pak nastavit právo pro zápis
(chmod) do složky, kam se budou sessions ukládat (což je defaultně složka writable/session). Druhou možností je ukládání sessions do
databáze a pak musíme vytvořit odpovídající databázovou tabulku (její struktura je popsaná v dokumentaci Codeigniteru v sekci o sessions).

Druhou důležitou věcí je nastavit dobu, po jaké session vyexpiruje. To bude znamenat, že to daného uživatele odhlásí a on se bude muset
přihlásit znovu. Session funuje tak, že po každém kliknutí se sama refreshuje (tzv. regeneruje) a ukládá si časový údaj o posledním refreshi.
Následně jej porovná s aktuálním časem naší následující akce (typicky kliknutí) a pokud je časový rozdíl větší než hodnota nastavená v

---

## Page 8

# 3. Přihlášení a odhlášení

Začneme jednoduššími částmi, kterými bude přihlášení a odhlášení. Se vším nám hodně pomůže knihovna Ion auth, na kterou budeme pořád
odkazovat.

---

## Page 9

# 3.1. Přihlášení

## Přihlášení probíhá typicky ve dvou krocích. Je to ostatně stejné jako u jiných formulářů. Prvním krokem je stránka s přihlašovacím formulářem a
## druhým je samotná kontrola přihlašovacích údajů a případné přihlášení (nebo přesměrování zpátky na přihlašovací formulář s hláškou o
## špatných údajích).

## Přihlašovací formulář není složitého, jedná se o běžný formulář, který by v této fázi každý už měl umět vytvořit. Bude mít dva inputy - jeden
## bude textový pro uživatelské jméno nebo email a druhý input bude pro heslo (input type password samozřejmě). A konečně tu bude odesílací
## tlačítko. A vše samozřejmě bude obaleno značkou form, která nás pošle na post routu, kde se naše přihlašovací údaje zpracují.

---

## Page 10

# controlleru. Nyní se podíváme na tuto metodu.

$login = $this->request->getPost('login');
$password = $this->request->getPost('password');

# atributy name) formulářových polí pro uživatelské jméno a heslo.
$logged = $this->ionAuth->login($login, $password);

# jestli je zadaná kombinace správná nebo ne.

if($logged){
return redirect()->route('administrace/dashboard');
} else {
$alert = $this->alert->makeMessage($logged, 'login');
$this->session->setFlashdata('alert', $alert);
return redirect()->route('prihlaseni');
}

# se nechám přesměrovat na dashboard, tj. nějakou úvodní stránku administrace.

---

# 3.2. Zpracování přihlášení

# Nyní se podíváme na to, jak zpracovat přihlašovací údaje. Budeme se bavit o nějaké post routě v controlleru, která nás převede na metodu v

# V první části metody zpracujeme přihlašovací údaje z formuláře a uložíme si je do proměnných. Údaje v závorkách getPost jsou názvy (=

# Tady zkontrolujeme, jestli uživatel zadal správnou kombinaci přihlašovacího jména a hesla. Pokud je kombinace správná, tak nás tato metoda
# přihlásí. Tento řádek předpokládá, že máme vytvořenou instanci třídy IonAuth (v mém případě je tato instance uložená v proměnné $this-
# >ionAuth, vytvořeno to bylo v konstruktoru BaseControlleru, protože to budu potřebovat ve více kontrolerech). Metoda login knihovny IonAuth
# má dva vstupní parametry, kterými jsou přihlašovací identita a přihlašovací heslo. Výsledkem této metody je pak true nebo false podle toho,

# Z kódu je vidět, že vůbec neřeším nějaké hashování, přestože heslo je v databázi opravdu zahashováno. To řeší za mě už zmíněná knihovna.

# Poslední částí metody je podmínka, kde řešíme, co mámě dělat v případě úspěšného přihlášení nebo v případě neúspěchu. V případě úspěchu

# V případě neúspěchu se nechám přesměrovat na úvodní stránku, ale musím si připravit chybovou hlášku s upozorněním, že se mně přihlášení
# nepodařilo. K tomu využívám třídu Alert, o které je psáno v části o formulářích. Následně data uložím do session a provedu přesměrování.

---

## Page 11

# 3.3. Odhlášení

## Odhlášení je podstatně jednodušší než přihlášení. Nejprve provedeme samotné odhlášení, o což se opět postará knihovna IonAuth.

$alert = $this->alert->makeMessage($logout, 'logout');

## A následně vytvoříme odpovídající alerty a postaráme se o přesměrování na správné adresy.
$alert = $this->alert->makeMessage($logout, 'logout');
$this->session->setFlashdata('alert', $alert);
if($logout){
return redirect()->route('prihlaseni');
} else {
return redirect()->route('dashboard');
}

---

## Page 12

# 3.4. Dostupnost rout

## Klíčovým prvkem při tvorbě administrace je zajištění toho, aby se do administrace uživatelé nedostali bez přihlášení. To bychom mohli řešit
## nějakými podmínkami v jednotlivých metodách, případně v konstruktorech kontrolerů. Ale to je všechno poměrně nepraktické,

## My si vytvoříme speciální prvek, tzv. middleware, který bude stát mezi routou a kontrolerem. A tento middleware bude kontrolovat, jestli je
## uživatel přihlášený a do administrace se může dostat. Stejně tak bychom si mohli vytvořit middleware na to, jestli uživatel má dostatečná práva
## (jestli je např. admin nebo jen obyčejný přihlášený uživatel). V CodeIgniteru se takovému middlewaru říká filtr.

## My ve filtru v podstatě řekneme, že daná routa (nebo skupina rout) má projít testem daného middlewaru a co se má stát, když se testem
## neprojde. Když se testem projde, tak jsme v pohodě a pustí nás to do Controlleru a stránka se zobrazí.

# Tvorba filtru

## Jako na mnoho jiných věcí na to použijeme terminál a příkaz php spark. Tentokrát napíšeme:

php spark make:filter název-filtru

## Filtrů můžeme mít klidně několik podle úrovní v systému. My si teď ukážeme filtr pro testování toho, jestli je uživatel přihlášený.

## Po vytvoření filtru se nám vytvoří nový soubor s třídou ve složce app/Filters. Filtr má dvě základní metody - before a after. V drtivé většině
## případů budeme používat before filtry, tzn. filtr, který proběhne ještě před samotným vytvářením response.

# Obsah metody before

## Metoda bude celkem jednoduchá, uděláme podmínku, jestli je uživatel přihlášený a pokud ne, tak nastavíme alerty a přesměrujeme ho na
## přihlašovací stránku. Je potřeba si uvědomit, že filtr není potomkem BaseControlleru, takže tu musíme znovu načíst session, knihovnu IonAuth a
## případné další knihovna.

$this->ionAuth = new IonAuth();
$this->session = \Config\Services::session();
$this->alert = new Alert();
if (!$this->ionAuth->loggedIn()) {
$alert = $this->alert->makeMessage(false, 'filter');
$this->session->setFlashdata('alert', $alert);
return redirect()->route('prihlaseni');
}

## Metoda loggedIn je opět součástí Ion Authu a testuje nám, jestli jsem přihlášení nebo ne.

# Povolení filtru

## Vnímavý čtenář jistě tuší, že to ještě nemůže být vše. Musíme nastavit, které routy budou daný filtr používat. Navíc musíme ještě filtr
## pojmenovat.
## Filtr pojmenujeme v souboru app/Config/Filters.php. Tam je pole $aliases, kde nastavíme přezdívku pro námi vytvořený filtr. Já jsem si ho
## pojmenoval takto:
'auth' => Auth::class,

## Tedy aliasem filtru je "auth", což budu používat v routách. Položkou za šipkou pak říkám, že pro tento alias bude platit třída filtrů, která se
## jmenuje Auth.
## Nyní se můžeme podívat do rout. Například všem routám z administrace budeme chtít nastavit výše vytvořený filtr s aliasem auth. To se dělá
## tzv. seskupováním rout.
$routes->group('administrace', ['filter' => 'auth'] , static function ($routes){
$routes->get('dashboard', 'Dashboard::index');
$routes->get('odhlaseni', 'Dashboard::logout');
$routes->get('profil/edit', 'Profile::edit');

});

## V kódu říkám, že na všechny routy, které obsahují na začátku routy slovo administrace budu aplikovat filtr s aliasem auth a následně mám
## soupis rout, pro které to bude platit. Jen do výpisu rout už nepíšu pokaždé to administrace, to už je tam automaticky v tom seskupení rout.
## Takže reálné routy jsou například administrace/dashboard. Takové routy pak musím psát u případných odkazů v rámci webu nebo u
## přesměrování.
## Jaké routy týkající se přihlášení a pod. filtrovat? Routy pro přihlášení určitě ne, routu pro odhlášení určitě ano, registraci opět ne, všechny routy z
## administrace opět ano.

---

## Page 13

# 4. Registrace

Při registraci musíme udělat několik věcí podobných jako při přihlášení, ale několik také nových. Vytvořit formulář pro registraci by nemělo být
nic těžkého, stejně tak zpracovat údaje. Novinkou by měly být validace - některé musí být lokální, jiné musí být serverové.

---

## Page 14

# 4.1. Formulář

Registrační formulář bude trochu složitější než přihlašovací, protože položek při registraci je víc než u přihlašovacího formuláře.

---

## Page 15

# 4.2. Validace

Validace je u registrace důležitá

---

## Page 16

# 4.3. Zpracování formuláře

to do