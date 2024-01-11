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
use arkania\webhook\AlreadyRegisteredException;
use arkania\webhook\WebhookManager;
use arkania\webhook\WebhookNamesKeys;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use ReflectionException;
use Symfony\Component\Filesystem\Path;

require_once __DIR__ . '/CoreConstants.php';
require_once __DIR__ . '/webhook/format/TextFormateur.php';
require_once __DIR__ . '/utils/promise/functions.php';

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
    private WebhookManager $webhookManager;

    /**
     * @throws BadExtensionException
     * @throws AlreadyInstantiatedException
     * @throws ReflectionException
     * @throws AlreadyRegisteredException
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
        $this->webhookManager = new WebhookManager($this);
        $this->serverLoader->loadEnginePlugins();
    }

    protected function onEnable() : void {
        $this->permissionManager->registerEnumPermission(PermissionsBase::cases());
        $this->serverLoader->enableEnginePlugins();

        $this->getWebhookManager()->getWebhook(WebhookNamesKeys::SERVER_START)->send(
            $this->getConfig()->get('server-name'),
            $this->getServer()->getIp(),
            $this->getServer()->getPort(),
            ProtocolInfo::CURRENT_PROTOCOL,
            ProtocolInfo::MINECRAFT_VERSION_NETWORK,
            $this->getApiVersion(),
            $this->getServer()->getMaxPlayers(),
            count($this->getServer()->getOnlinePlayers()),
            PHP_VERSION
        );
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

    public function getWebhookManager() : WebhookManager {
        return $this->webhookManager;
    }

}