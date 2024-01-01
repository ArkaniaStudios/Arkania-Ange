<?php
declare(strict_types=1);

namespace arkania\plugins;

use arkania\Engine;
use arkania\lang\KnownTranslationsFactory;
use arkania\utils\AlreadyInstantiatedException;
use arkania\utils\BadExtensionException;
use pocketmine\Server;
use ReflectionException;
use Symfony\Component\Filesystem\Path;

class ServerLoader {

    private PluginManager $pluginManager;

    /**
     * @throws BadExtensionException
     * @throws AlreadyInstantiatedException
     */
    public function __construct(
        private readonly Engine $engine,
        private readonly Server $server
    ) {
        $this->pluginManager = new PluginManager(
            $this->engine,
            $this->server
        );
    }

    /**
     * @throws ReflectionException
     */
    public function loadEnginePlugins() : void {
        $path = Path::join($this->server->getDataPath(), 'engine-plugins');

        $scanDir = scandir($path);
        if ($scanDir === false) {
            $this->engine->getLogger()->error(
                $this->engine->getLanguage()->translate(
                    KnownTranslationsFactory::plugin_load_error()
                )
            );
            return;
        }
        foreach (array_diff($scanDir, ['.', '..']) as $name) {
            $loader = new FolderPluginLoader($this->server->getLoader());
            $infos  = $loader->getPluginInfo(
                Path::join($path, $name)
            );
            if ($infos === null) {
                continue;
            }
            $this->pluginManager->loadPlugins($path);
        }
    }

    public function enableEnginePlugins() : bool {
        $allSuccess = true;
        foreach ($this->pluginManager->getPlugins() as $plugin) {
            if(!$plugin->isEnabled()) {
                if($this->pluginManager->enablePlugin($plugin) === false) {
                    $allSuccess = false;
                }
            }
        }
        return $allSuccess;
    }

    public function disableEnginePlugins() : void {
        $this->pluginManager->disablePlugins();
    }

    public function getPluginManager() : PluginManager {
        return $this->pluginManager;
    }


}