<?php

/*
 *     _      ____    _  __     _      _   _   ___      _              _____   _   _    ____   ___   _   _   _____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            | ____| | \ | |  / ___| |_ _| | \ | | | ____|
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____  |  _|   |  \| | | |  _   | |  |  \| | |  _|
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____| | |___  | |\  | | |_| |  | |  | |\  | | |___
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\         |_____| |_| \_|  \____| |___| |_| \_| |_____|
 *
 * ArkaniaStudios-ANGE, une API conçue pour simplifier le développement.
 * Fournissant des outils et des fonctionnalités aux développeurs.
 * Cet outil est en constante évolution et est régulièrement mis à jour,
 * afin de répondre aux besoins changeants de la communauté.
 *
 * @author Julien
 * @link https://arkaniastudios.com
 * @version 0.2.0-beta
 *
 */

declare(strict_types=1);

namespace arkania;

use arkania\commands\CommandCache;
use arkania\commands\default\LanguageCommand;
use arkania\commands\default\MaintenanceCommand;
use arkania\commands\default\NpcCommand;
use arkania\commands\default\PluginCommand;
use arkania\commands\default\ReplyCommand;
use arkania\commands\default\TellCommand;
use arkania\commands\default\VersionCommand;
use arkania\commands\listener\CommandDataListener;
use arkania\database\DataBaseManager;
use arkania\database\result\SqlSelectResult;
use arkania\events\ListenerManager;
use arkania\item\ItemManager;
use arkania\item\listener\DataPacketSendListener;
use arkania\lang\Language;
use arkania\lang\LanguageManager;
use arkania\listener\PlayerChangeLanguageListener;
use arkania\listener\PlayerJoinListener;
use arkania\listener\PlayerReceiveFormListener;
use arkania\network\server\EngineServer;
use arkania\network\server\ServerInterface;
use arkania\network\server\ServerManager;
use arkania\network\server\ServersIds;
use arkania\packs\ResourcePackManager;
use arkania\player\permissions\MissingPermissionException;
use arkania\player\permissions\PermissionsBase;
use arkania\player\permissions\PermissionsManager;
use arkania\plugins\ServerLoader;
use arkania\utils\AlreadyInstantiatedException;
use arkania\utils\BadExtensionException;
use arkania\utils\Loader;
use arkania\webhook\AlreadyRegisteredException;
use arkania\webhook\WebhookManager;
use arkania\webhook\WebhookNamesKeys;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use ReflectionException;
use Symfony\Component\Filesystem\Path;

use function count;

use const PHP_VERSION;

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
	private ServerManager $serverManager;
	private CommandCache $commandCache;
	private ItemManager $itemManager;
	private ResourcePackManager $resourcePackManager;

	/**
	 * @throws BadExtensionException
	 * @throws AlreadyInstantiatedException
	 * @throws ReflectionException
	 * @throws AlreadyRegisteredException
	 */
	protected function onLoad() : void {
		self::setInstance($this);

		$this->saveResource("config.yml", true);
		$this->pluginPath          = Path::join($this->getServer()->getDataPath(), 'engine-plugins');
		$this->dataBaseManager     = new DataBaseManager($this);
		$this->languageManager     = new LanguageManager($this);
		$this->serverLoader        = new ServerLoader($this, $this->getServer());
		$this->permissionManager   = new PermissionsManager();
		$this->listenerManager     = new ListenerManager();
		$this->webhookManager      = new WebhookManager($this);
		$this->serverManager       = new ServerManager();
		$this->commandCache        = new CommandCache($this);
		$this->itemManager         = new ItemManager();
		$this->resourcePackManager = new ResourcePackManager($this);
		new Loader();

		$this->getServerManager()->addServer(
			new EngineServer(
				$this,
				1,
				$this->getConfig()->get('server-name'),
				'arkaniastudios.com',
				19132,
				ServerInterface::STATUS_ONLINE
			)
		);

		$this->serverLoader->loadEnginePlugins();
	}

	/**
	 * @throws MissingPermissionException
	 */
	protected function onEnable() : void {
		$this->permissionManager->registerEnumPermission(PermissionsBase::cases());

		$this->getCommandCache()->unregisterCommands(
			'version',
			'tell',
			'pl'
		);

		$this->getCommandCache()->registerCommands(
			new LanguageCommand(),
			new NpcCommand(),
			new MaintenanceCommand($this),
			new PluginCommand(),
			new ReplyCommand(),
			new TellCommand(),
			new VersionCommand()
		);

		$this->getListenerManager()->registerListeners(
			new PlayerJoinListener(),
			new PlayerReceiveFormListener(),
			new DataPacketSendListener(),
			new PlayerChangeLanguageListener(),
			new DataPacketSendListener(),
		);

		$this->serverLoader->enableEnginePlugins();

		if(VersionInfo::IS_DEVELOPMENT_BUILD) {
			$this->getServer()->getLogger()->info('§6Engine is running on development build');
		} else {
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

		$this->getResourcePackManager()->loadResourcePack();

		new CommandDataListener($this);
	}

	protected function onDisable() : void {
		$server = $this->getServerManager()->getServer(
			ServersIds::getIdWithPort(
				$this->getServer()->getPort()
			)
		);
		$server->getStatus()->then(function (SqlSelectResult $result) use ($server) {
			if(count($result->getRows()) <= 0) {
				return;
			}
			if($result->getRows()[0]['status'] === ServerInterface::STATUS_WHITELIST) {
				return;
			}
			$server->setStatus(ServerInterface::STATUS_OFFLINE);
		});

		$this->serverLoader->disableEnginePlugins();
	}

	final public function getEngineFile() : string {
		return Path::join(
			$this->getServer()->getPluginPath(),
			'Arkania-ANGE',
			'src'
		);
	}

	public function getPluginPath() : string {
		return $this->pluginPath;
	}

	public function getApiVersion() : string {
		return VersionInfo::BASE_VERSION;
	}

	public function getServerName() : string {
		return $this->getConfig()->get('server-name');
	}

	public function getLanguageManager() : LanguageManager {
		return $this->languageManager;
	}

	public function getLanguage() : Language {
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

	public function getServerManager() : ServerManager {
		return $this->serverManager;
	}

	public function getCommandCache() : CommandCache {
		return $this->commandCache;
	}

	public function getItemManager() : ItemManager {
		return $this->itemManager;
	}

	public function getResourcePackManager() : ResourcePackManager {
		return $this->resourcePackManager;
	}
}
