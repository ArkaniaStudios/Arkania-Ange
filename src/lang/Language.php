<?php
declare(strict_types=1);

namespace arkania\lang;

use pocketmine\lang\LanguageNotFoundException;
use pocketmine\lang\Translatable;
use pocketmine\utils\Utils;
use Symfony\Component\Filesystem\Path;

use const pocketmine\LOCALE_DATA_PATH;

class Language extends \pocketmine\lang\Language {

    public function __construct(
        string $langName,
        string $lang,
        ?string $path = null,
        ?string $fallbackPath = null,
        string $fallback = \pocketmine\lang\Language::FALLBACK_LANGUAGE
    ) {
        $this->langName = $langName;

        if($path === null){
            $path = LOCALE_DATA_PATH;
        }

        $this->lang = self::loadLang($path, $lang);
        $this->fallbackLang = self::loadLang($fallbackPath, $fallback);
    }

    public function getName() : string {
        return $this->langName;
    }

    public function getLang() : string {
        return $this->get(KnownTranslationsKeys::LANGUAGE_NAME);
    }

    protected static function loadLang(string $path, string $languageCode) : array {
        $file = Path::join($path, $languageCode);
        if(file_exists($file)) {
            $strings = array_map('stripcslashes', Utils::assumeNotFalse(parse_ini_file($file, false, INI_SCANNER_RAW), "Missing or inaccessible required resource files"));
            if(count($strings) > 0) {
                return $strings;
            }
        }
        throw new LanguageNotFoundException("Language \"$languageCode\" not found");
    }

    public function translate(Translatable $c) : string {
        $baseText = $this->internalGet($c->getText());
        if($baseText === null) {
            $baseText = $this->parseTranslation($c->getText());
        }

        foreach($c->getParameters() as $i => $p) {
            $replacement = $p instanceof Translatable ? $this->translate($p) : $p;
            $baseText    = str_replace("{%$i}", $replacement, $baseText);
            $baseText    = str_replace('\n', "\n", $baseText);
        }

        return $baseText;
    }

}