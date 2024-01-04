<?php
declare(strict_types=1);

namespace arkania;

use arkania\database\DataBaseManager;
use arkania\events\ListenerManager;
use arkania\lang\Language;
use arkania\lang\LanguageManager;
use arkania\player\permissions\PermissionsBase;
use arkania\player\permissions\PermissionsManager;
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
    private DataBaseManager $dataBaseManager;

    /**
     * @throws BadExtensionException
     * @throws AlreadyInstantiatedException
     * @throws ReflectionException
     */
    protected function onLoad() : void {
        self::setInstance($this);

        $this->saveResource("config.yml", true);
        $this->pluginPath = Path::join($this->getServer()->getDataPath(), 'engine-plugins');
        $this->dataBaseManager = new DataBaseManager($this);
        $this->languageManager = new LanguageManager($this);
        $this->serverLoader = new ServerLoader($this, $this->getServer());
        $this->permissionManager = new PermissionsManager();
        $this->listenerManager = new ListenerManager();
        $this->serverLoader->loadEnginePlugins();
    }

    protected function onEnable() : void {
        $this->permissionManager->registerEnumPermission(PermissionsBase::cases());
        $this->serverLoader->enableEnginePlugins();

        $this->getDataBaseManager()->getConnector()->executeSelect(
            'SELECT language FROM languages WHERE player_name = ?',
            ['Julien8436'],
        )->then(function (array $rows){
            var_dump($rows);
        });

    }

    protected function onDisable() : void {
       $this->serverLoader->disableEnginePlugins();
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

    public function getDataBaseManager() : DataBaseManager {
        return $this->dataBaseManager;
    }

}