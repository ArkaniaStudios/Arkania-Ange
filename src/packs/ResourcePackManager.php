<?php
declare(strict_types=1);

namespace arkania\packs;

use arkania\Engine;
use pocketmine\resourcepacks\ZippedResourcePack;
use Symfony\Component\Filesystem\Path;

class ResourcePackManager {

    private Engine $engine;

    /** @var string[] */
    protected array $resourcePackPath;

    public function __construct(
        Engine $engine
    ) {
        $this->engine           = $engine;
        $this->resourcePackPath = [];
        $this->registerResourcePack(
            'Arkania-Pack',
            new ResourcesPackFile(
                Path::join($engine->getEngineFile(), 'packs', 'Arkania-Pack')
            )
        );
    }

    public function registerResourcePack(string $packName, ResourcesPackFile $packFile) : void {
        $this->resourcePackPath[$packName] = $packFile->getResourcePackPath();
        $packFile->savePackInData($packFile->getResourcePackPath());
        $packFile->zipPack(
            $packFile->getResourcePackPath(),
            Path::join($this->engine->getEngineFile(), 'packs'),
            $packName
        );
    }

    public function loadResourcePack() : void {
        $resourcePackManager = $this->engine->getServer()->getResourcePackManager();
        $resourcePacks       = [];
        foreach ($this->resourcePackPath as $packName => $packPath) {
            $resourcePacks[] = new ZippedResourcePack($packPath . '.zip');
        }
        $ev              = new ResourcePackLoadEvent();
        $ev->call();
        if (!$ev->isCancelled()) {
            if ($ev->getResourcePackPath() !== null) {
                foreach ($ev->getResourcePackPath() as $packName => $resource) {
                    $resourcePacks[] = new ZippedResourcePack($resource . '.zip');
                }
            }
            $resourcePackManager->setResourcePacksRequired(true);
            $resourcePackManager->setResourceStack($resourcePacks);
        } else {
            $this->engine->getLogger()->warning('Resources pack system is cancelled !');
        }
    }

}