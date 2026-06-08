# Music Archive

Webová aplikace sloužící jako hudební archiv inspirovaný Discogs / MusicBrainz. Uživatelé mohou procházet umělce, alba a skladby, filtrovat podle žánru nebo roku vydání a zobrazovat detaily včetně popisu, obalu alba a průměrného hodnocení. Přihlášení administrátoři mohou záznamy přidávat, editovat a mazat.

---

## Tým a rozdělení práce

| Jméno | Část projektu |
|---|---|
| Marek Pokorný | Backend: AlbumController, AlbumLib, AlbumModel, TrackController, TrackModel, AuthController, UserModel, AuthFilter, ReviewController, ReviewModel, Routes, Seeder, PDF generování (Dompdf) |
| Tomáš Kokotuč | Backend: ArtistController, ArtistLib, ArtistModel, GenreController, GenreModel, migrace databáze, Frontend: všechny Views (layout, artist, album, genre, track, auth), CSS, Bootstrap integrace |

> **Poznámka:** Výše uvedené rozdělení je přibližné – oba členové se podíleli na všech částech, ale každý nesl hlavní odpovědnost za uvedené části.

---

## Názvosloví — databáze

| Věc | Konvence | Příklad |
|---|---|---|
| Jazyk tabulek | angličtina | `artist`, `album` |
| Notace tabulek | snake_case, jednotné číslo | `album`, `genre` |
| Primární klíč | `id` | `id INT AUTO_INCREMENT` |
| Cizí klíč | `{singular}_id` | `artist_id`, `genre_id` |
| Víceslovné názvy sloupců | snake_case | `release_year`, `cover_image`, `deleted_at` |
| Soft delete | `deleted_at` | `deleted_at DATETIME NULL` |
| Timestamps | `created_at`, `updated_at` | `created_at DATETIME` |

---

## Názvosloví — PHP / CodeIgniter 4

| Věc | Konvence | Příklad |
|---|---|---|
| Proměnné | camelCase | `$albumList`, `$currentUser` |
| Data do view (array klíče) | snake_case | `$data['album_list']`, `$data['genre_options']` |
| Konstanty | UPPER_SNAKE_CASE | `ITEMS_PER_PAGE`, `UPLOAD_PATH` |
| Třídy (Controllers) | PascalCase + přípona Controller | `AlbumController` |
| Třídy (Models) | PascalCase + přípona Model | `AlbumModel` |
| Třídy (Libraries) | PascalCase + přípona Lib | `AlbumLib` |
| Složky Views | malá písmena dle controlleru | `views/album/index.php` |
| Layout složka | `views/layout/` | `template.php` |

---

## Názvosloví — metody v controllerech

| Věc | Konvence | Příklad |
|---|---|---|
| Notace metod | camelCase | `index()`, `show()`, `create()` |
| Názvy metod | angličtina | `store()`, `edit()`, `update()`, `delete()` |
| Notace rout | snake_case, jednotné číslo | `/album`, `/album/create`, `/album/(:num)/edit` |
| Parametry v routách | `(:num)` pro ID | `/album/(:num)/track/(:num)/edit` |

---

## Struktura databáze

```
artist        — umělci / kapely (name, photo, bio, country, formed_year, deleted_at...)
album         — alba (title, release_date, cover_image, description, label, artist_id...)
track         — skladby (title, duration, track_number, album_id...)
genre         — hudební žánry (name, description...)
artist_genre  — vazební tabulka umělec ↔ žánr (artist_id, genre_id)
review        — recenze alb (rating, body, album_id, user_id, UNIQUE na user+album...)
user          — uživatelé (username, email, password_hash, role[user/admin]...)
```

---

## Konfigurační proměnné (`app/Config/MusicArchive.php`)

| Proměnná | Typ | Výchozí hodnota | Popis |
|---|---|---|---|
| `$itemsPerPage` | int | `12` | Počet alb zobrazených na jedné stránce v přehledu karet. Možné hodnoty: libovolné kladné celé číslo (doporučeno 6–24). |
| `$uploadPath` | string | `WRITEPATH . 'uploads/'` | Absolutní cesta k adresáři pro nahrané soubory (fotky umělců, obaly alb). Relativní k `WRITEPATH` (složka `writable/`). |
| `$allowedTypes` | string | `'jpg\|png\|webp'` | Povolené přípony souborů pro upload obrázků, oddělené `\|`. Možné hodnoty: kombinace `jpg`, `png`, `webp`, `gif`. |
| `$maxFileSize` | int | `2048` | Maximální velikost nahrávaného souboru v kilobytech. Hodnota `2048` = 2 MB. |

---

## Popis controllerů a jejich metod

### ArtistController

Stará se o výpis, detail, přidávání, editaci a mazání umělců.

| Metoda | Popis |
|---|---|
| `index()` | Vypíše seznam všech umělců jako tabulku. |
| `show(int $id)` | Zobrazí detail umělce – bio, žánry, alba. |
| `create()` | Zobrazí formulář pro přidání umělce (načítá žánry z DB pro Select2). |
| `store()` | Zpracuje POST a uloží nového umělce (deleguje na ArtistLib). |
| `edit(int $id)` | Zobrazí formulář pro editaci s předvyplněnými daty. |
| `update(int $id)` | Zpracuje POST a aktualizuje umělce. |
| `delete(int $id)` | Provede soft delete umělce (volá se z modálního okna). |

### AlbumController

Spravuje výpis, detail, CRUD a PDF export alb.

| Metoda | Popis |
|---|---|
| `index()` | Zobrazí stránkovaný seznam alb jako karty. Podporuje GET filtry `genre_id` a `year`. Počet na stránku z configu. |
| `show(int $id)` | Detail alba – JOIN artist, AVG hodnocení, tracklist, recenze (deleguje na AlbumLib). |
| `create()` | Formulář pro přidání alba (načítá umělce z DB pro dropdown). |
| `store()` | Uloží nové album (deleguje na AlbumLib). |
| `edit(int $id)` | Formulář pro editaci alba. |
| `update(int $id)` | Aktualizuje album. |
| `delete(int $id)` | Soft delete alba. |
| `pdf(int $id)` | Vygeneruje PDF soubor s informacemi o albu a stáhne jej přes Dompdf. |

### GenreController

Pouze veřejné zobrazení žánrů (bez CRUD).

| Metoda | Popis |
|---|---|
| `index()` | Seznam všech žánrů jako karty, zobrazuje počet umělců (použit COUNT + GROUP BY). |
| `show(int $id)` | Detail žánru a umělci patřící do tohoto žánru (JOIN přes artist_genre). |

### TrackController

CRUD pro skladby, vždy nested pod album (2 parametry v routě).

| Metoda | Popis |
|---|---|
| `create(int $albumId)` | Formulář pro přidání skladby k albu. |
| `store(int $albumId)` | Uloží skladbu, redirect na album. |
| `edit(int $albumId, int $id)` | Formulář pro editaci skladby. |
| `update(int $albumId, int $id)` | Aktualizuje skladbu, redirect na album. |
| `delete(int $albumId, int $id)` | Soft delete skladby, redirect na album. |

### AuthController

Přihlášení, registrace a odhlášení. Login funguje s emailem i uživatelským jménem.

| Metoda | Popis |
|---|---|
| `login()` | Zobrazí přihlašovací formulář. |
| `loginPost()` | Ověří heslo (`password_verify`), nastaví session. |
| `register()` | Zobrazí registrační formulář. |
| `registerPost()` | Validuje vstup (unikátní email/username, min. délka hesla), uloží uživatele. |
| `logout()` | Zničí session a přesměruje na login. |

### ReviewController

Přidávání a mazání recenzí (zobrazení je na stránce alba).

| Metoda | Popis |
|---|---|
| `store(int $albumId)` | Uloží recenzi přihlášeného uživatele (kontroluje UNIQUE – max 1 recenze na album). |
| `delete(int $albumId, int $id)` | Soft delete recenze (pouze admin). |

---

## Popis vlastních knihoven

### ArtistLib (`app/Libraries/ArtistLib.php`)

Knihovna pro operace s umělci. Odděluje business logiku od controlleru.

| Metoda | Parametry | Výstup | Popis |
|---|---|---|---|
| `getAll()` | — | `array` | Všichni umělci seřazení dle jména. |
| `getById(int $id)` | `$id` – ID umělce | `array\|null` | Jeden umělec nebo null. |
| `create(array $data, $file)` | `$data` – POST data, `$file` – UploadedFile nebo null | `bool` | Uloží umělce, nahraje foto, synchronizuje žánry. |
| `update(int $id, array $data, $file)` | `$id`, `$data`, `$file` | `bool` | Aktualizuje umělce, případně smaže starý obrázek. |
| `delete(int $id)` | `$id` – ID | `bool` | Soft delete (nastaví `deleted_at`). |
| `getErrors()` | — | `array` | Validační chyby z modelu. |
| `syncGenres(int $artistId, array $genreIds)` | `$artistId`, `$genreIds` – pole ID | `void` | Synchronizuje žánry – smaže stávající, vloží nové záznamy do `artist_genre`. |
| `getGenreIds(int $artistId)` | `$artistId` | `array` | Pole ID žánrů přiřazených umělci. |

### AlbumLib (`app/Libraries/AlbumLib.php`)

Knihovna pro operace s alby – upload obálky, JOINy, filtrování, stránkování.

| Metoda | Parametry | Výstup | Popis |
|---|---|---|---|
| `getPaginated(array $filters, int $perPage)` | `$filters` – `genre_id`, `year`; `$perPage` | `array` | Stránkovaný seznam alb s JOIN na artist. Vrací `['albums', 'pager', 'total', 'perPage', 'page']`. |
| `getAll()` | — | `array` | Všechna alba s jménem umělce (pro dropdowny). |
| `getById(int $id)` | `$id` | `array\|null` | Základní data alba. |
| `getWithDetails(int $id)` | `$id` | `array\|null` | Album s artist JOIN, AVG(rating), tracklist, recenze. |
| `create(array $data, $file)` | `$data`, `$file` | `bool` | Uloží album, nahraje obálku. |
| `update(int $id, array $data, $file)` | `$id`, `$data`, `$file` | `bool` | Aktualizuje album, případně smaže starý obrázek. |
| `delete(int $id)` | `$id` | `bool` | Soft delete. |
| `getErrors()` | — | `array` | Validační chyby. |

---

## Externí nástroje a knihovny

| Název | Verze | Autor | Licence | Odkaz |
|---|---|---|---|---|
| CodeIgniter 4 | 4.6.x | British Columbia Institute of Technology | MIT | https://codeigniter.com |
| Bootstrap | 5.3.3 | The Bootstrap Authors | MIT | https://getbootstrap.com |
| Bootstrap Icons | 1.11.3 | The Bootstrap Authors | MIT | https://icons.getbootstrap.com |
| FontAwesome Free | 6.5.2 | Fonticons, Inc. | Free License | https://fontawesome.com |
| TinyMCE | 7 | Tiny Technologies Inc. | MIT | https://tiny.cloud |
| Select2 | 4.1.0-rc.0 | Kevin Brown, Igor Vaynberg | MIT | https://select2.org |
| Select2 Bootstrap 5 Theme | 1.3.0 | Apalfrey | MIT | https://github.com/apalfrey/select2-bootstrap-5-theme |
| jQuery | 3.7.1 | OpenJS Foundation | MIT | https://jquery.com |
| Dompdf | 3.1.x | Dompdf Contributors | LGPL 2.1 | https://github.com/dompdf/dompdf |

---

## Programátorská složitost – splnění požadavků

| Požadavek | Kde v kódu |
|---|---|
| Stránka s JOINem | `AlbumLib::getWithDetails()` – JOIN album + artist; `AlbumLib::getPaginated()` – JOIN album + artist; `GenreController::show()` – JOIN artist + artist_genre |
| Agregační funkce | `AlbumLib::getWithDetails()` – `AVG(review.rating)`, `COUNT(DISTINCT review.id)`; `GenreController::index()` – `COUNT(artist_genre.artist_id)` |
| Routa se 2+ parametry | `album/(:num)/track/(:num)/edit`, `album/(:num)/track/(:num)/update`, `album/(:num)/track/(:num)/delete`, `album/(:num)/review/(:num)/delete` |
| Soft delete s datetime | Všechny tabulky mají `deleted_at DATETIME NULL`, CI4 Model nastavuje automaticky |
| Delete s modálním oknem | `Views/artist/index.php`, `Views/album/index.php`, `Views/album/show.php` |
| Dropdown z DB | `Views/album/create.php` – výběr umělce načítán z DB, první možnost `disabled selected` |
| WYSIWYG editor | TinyMCE v `Views/artist/create.php`, `edit.php`, `Views/album/create.php`, `edit.php` |
| Upload souboru | `ArtistLib::create/update()` – foto umělce; `AlbumLib::create/update()` – obálka alba |
| Select2 multiselect | `Views/artist/create.php`, `edit.php` – výběr žánrů |
| PDF generování | `AlbumController::pdf()` – Dompdf, stažení souboru |
| Login s username i emailem | `UserModel::findByLogin()` – `WHERE email = ? OR username = ?` |
| Registrace s validací | `AuthController::registerPost()` – validace délky, unikátnosti, hesla |
| Zobrazit/skrýt heslo | `Views/auth/login.php`, `Views/auth/register.php` – JS toggle |

---

## Přihlašovací údaje pro testování

| Role | Email | Heslo |
|---|---|---|
| Admin | admin@example.com | admin123 |
| Uživatel | user1@example.com | heslo123 |

*(Vloží se přes Seeder: `php spark db:seed MusicArchiveSeeder`)*

---

## Zdroje a použité materiály

- CodeIgniter 4 User Guide – https://codeigniter.com/user_guide/
- Bootstrap 5 dokumentace – https://getbootstrap.com/docs/5.3/
- Select2 dokumentace – https://select2.org/
- Dompdf GitHub – https://github.com/dompdf/dompdf
- TinyMCE dokumentace – https://www.tiny.cloud/docs/

*(Pokud byly při vývoji použity fóra nebo Stack Overflow, doplňte konkrétní odkazy zde.)*

---

## Přílohy

*(Screenshoty z AI chatu přiložit jako obrázky do složky `prilohy/` a zde odkazovat, pokud bylo AI využito.)*
