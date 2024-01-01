<?php
declare(strict_types=1);

namespace arkania\plugins;

use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use pocketmine\plugin\PluginDescriptionParseException;
use pocketmine\thread\ThreadSafeClassLoader;
use Symfony\Component\Filesystem\Path;

class FolderPluginLoader implements PluginLoader {

    public function __construct(
        private readonly ThreadSafeClassLoader $loader
    ) {}

    public function canLoad(string $path) : bool {
        return is_dir($path) && file_exists(Path::join($path, "/plugin.yml"));
    }
    public function loadPlugin(string $file) : void {
        $description = $this->getPluginInfo($file);
        if($description !== null) {
            $this->loader->addPath($description->getSrcNamespacePrefix(), "$file/src");
        }
    }
    public function getPluginInfo(string $file) : ?PluginInformations {
        if(is_dir($file) and file_exists($file . "/plugin.yml")) {
            $yaml = @file_get_contents($file . "/plugin.yml");
            if($yaml !== '') {
                try {
                    return new PluginInformations($yaml);
                } catch (PluginDescriptionParseException) {
                    Engine::getInstance()->getLogger()->error(
                        Engine::getInstance()->getLanguage()->translate(
                            KnownTranslationsFactory::plugin_invalid_plugin_file(
                                $file . "/plugin.yml"
                            )
                        )
                    );
                    return null;
                }
            }
        }
        return null;
    }

    public function getAccessProtocol() : string {
        return "";
    }

}