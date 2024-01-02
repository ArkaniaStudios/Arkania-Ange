<?php
declare(strict_types=1);

namespace arkania;

use arkania\events\ListenerManager;
use arkania\lang\KnownTranslationsFactory;
use arkania\lang\Language;
use arkania\lang\LanguageManager;
use arkania\player\permissions\PermissionsBase;
use arkania\player\permissions\PermissionsManager;
use arkania\player\Session;
use arkania\plugins\ServerLoader;
use arkania\utils\AlreadyInstantiatedException;
use arkania\utils\BadExtensionException;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use ReflectionException;
use Symfony\Component\Filesystem\Path;

require_once __DIR__ . '/CoreConstants.php';

class Engine extends PluginBase {
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    private string $pluginPath;
    private LanguageManager $languageManager;
    private ServerLoader $serverLoader;
    private PermissionsManager $permissionManager;
    private ListenerManager $listenerManager;

    /**
     * @throws BadExtensionException
     * @throws AlreadyInstantiatedException
     * @throws ReflectionException
     */
    protected function onLoad() : void {
        self::setInstance($this);

        $this->saveResource("config.yml", true);
        $this->pluginPath = Path::join($this->getServer()->getDataPath(), 'engine-plugins');
        $this->languageManager = new LanguageManager($this);
        $this->serverLoader = new ServerLoader($this, $this->getServer());
        $this->permissionManager = new PermissionsManager();
        $this->listenerManager = new ListenerManager();
        $this->serverLoader->loadEnginePlugins();
    }

    protected function onEnable() : void {
        $this->permissionManager->registerEnumPermission(PermissionsBase::cases());
        $this->serverLoader->enableEnginePlugins();
    }

    protected function onDisable() : void {
       $this->serverLoader->disableEnginePlugins();
       foreach ($this->getServer()->getOnlinePlayers() as $player) {
           Session::get($player)->disconnect(
               KnownTranslationsFactory::plugin_server_closed()
           );
       }
    }

    public function getPluginPath() : string {
        return $this->pluginPath;
    }

    public function getApiVersion() : string {
        return VersionInfo::BASE_VERSION;
    }

    public function getLanguageManager() : LanguageManager {
        return $this->languageManager;
    }

    public function getLanguage(): Language {
        return $this->getLanguageManager()->getConsoleLanguage();
    }

    public function getServerLoader() : ServerLoader {
        return $this->serverLoader;
    }

    public function getPermissionsManager() : PermissionsManager {
        return $this->permissionManager;
    }

    public function getListenerManager() : ListenerManager {
        return $this->listenerManager;
    }

}