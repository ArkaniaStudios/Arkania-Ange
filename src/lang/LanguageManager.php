<?php
declare(strict_types=1);

namespace arkania\lang;

use arkania\Engine;
use arkania\events\lang\RegisterLanguageEvent;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Path;


class LanguageManager implements NotOtherInstanceInterface{
    use NotOtherInstanceTrait {
        NotOtherInstanceTrait::__construct as private __notOtherInstanceTraitConstruct;
    }

    /** @var array<string, Language> */
    private array $languages = [];
    private Engine $engine;

    public function __construct(
        Engine $engine
    ) {
        $this->__notOtherInstanceTraitConstruct();

        if(!file_exists(Path::join($engine->getDataFolder(), 'data'))){
            mkdir(Path::join($engine->getDataFolder(), 'data'));
        }
        $engine->saveResource(Path::join('data', 'fr_FR.lang'), true);

        $this->register(
            new Language(
                'Français',
                'fr_FR.lang',
                Path::join($engine->getDataFolder(), 'data'),
                Path::join(dirname(__DIR__, 2), 'vendor', 'pocketmine', 'locale-data'),
                'fra.ini'
            )
        );
        $this->engine = $engine;
    }

    /**
     * @param Language $language
     * @return void
     * @throws InvalidArgumentException
     */
    public function register(Language $language) : void {
        if(isset($this->languages[$language->getName()])) {
            throw new InvalidArgumentException("Language with name {$language->getName()} already registered");
        }
        $ev = new RegisterLanguageEvent($language);
        $ev->call();
        if($ev->isCancelled()) {
            return;
        }
        $this->languages[$language->getName()] = $language;
    }

    /**
     * @param string $lang
     * @return Language|null
     */
    public function getLanguage(string $lang) : ?Language {
        return $this->languages[$lang] ?? null;
    }

    public function getConsoleLanguage() : Language {
        return $this->languages[self::parseLanguageName($this->engine->getConfig()->get('console-language'))];
    }

    public function getDefaultLanguage() : Language {
        return $this->languages[self::parseLanguageName($this->engine->getConfig()->get('default-language'))];
    }

    public static function parseLanguageName(string $lang) : string {
        return match($lang) {
            'english' => 'English',
            default => 'Français'
        };
    }

}