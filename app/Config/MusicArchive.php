<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class MusicArchive extends BaseConfig
{
    /**
     * Počet záznamů zobrazených na jedné stránce (karty alb, seznam umělců).
     * Možné hodnoty: libovolné kladné celé číslo (doporučeno 6–24).
     */
    public int $itemsPerPage = 12;

    /**
     * Cesta k adresáři pro nahrané soubory (fotky umělců, obaly alb).
     * Relativní k WRITEPATH (= writable/).
     */
    public string $uploadPath = WRITEPATH . 'uploads/';

    /**
     * Povolené přípony souborů pro upload obrázků (oddělené svislítkem).
     * Možné hodnoty: kombinace 'jpg', 'png', 'webp', 'gif'.
     */
    public string $allowedTypes = 'jpg|png|webp';

    /**
     * Maximální velikost nahrávaného souboru v kilobytech.
     * Možné hodnoty: kladné celé číslo (2048 = 2 MB).
     */
    public int $maxFileSize = 2048;
}
