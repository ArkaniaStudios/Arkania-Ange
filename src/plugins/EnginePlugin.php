<?php
declare(strict_types=1);

namespace arkania\plugins;

use arkania\Engine;
use AttachableLogger;
use pocketmine\plugin\ResourceProvider;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;

interface EnginePlugin {

    public function __construct(
        Engine $engine,
        PluginLoader $loader,
        Server $server,
        PluginInformations $informations,
        string $dataFolder,
        string $file,
        ResourceProvider $resourceProvider
    );

    public function isEnabled() : bool;

    public function onEnableStateChange(bool $enabled) : void;

    public function getDataFolder() : string;

    public function getInformations() : PluginInformations;

    public function getName() : string;

    public function getLogger() : AttachableLogger;

    public function getLoader() : PluginLoader;

    public function getEngine() : Engine;

    public function getScheduler() : TaskScheduler;

}