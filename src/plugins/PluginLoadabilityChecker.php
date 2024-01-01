<?php
declare(strict_types=1);

namespace arkania\plugins;

use pocketmine\lang\KnownTranslationFactory;
use pocketmine\lang\Translatable;
use pocketmine\plugin\ApiVersion;
use pocketmine\utils\VersionString;

class PluginLoadabilityChecker {

    public function __construct(
        private readonly string $apiVersion
    ) {}

    public function check(PluginInformations $informations) : Translatable|null {
        $name = $informations->getName();
        if(stripos($name, "pocketmine") !== false || stripos($name, "minecraft") !== false || stripos($name, "mojang") !== false || stripos($name, 'arkania') !== false){
            return KnownTranslationFactory::pocketmine_plugin_restrictedName();
        }

        foreach($informations->getApi() as $api){
            if(!VersionString::isValidBaseVersion($api)){
                return KnownTranslationFactory::pocketmine_plugin_invalidAPI($api);
            }
        }

        if(!ApiVersion::isCompatible($this->apiVersion, $informations->getApi())){
            return KnownTranslationFactory::pocketmine_plugin_incompatibleAPI(implode(", ", $informations->getApi()));
        }

        $ambiguousVersions = ApiVersion::checkAmbiguousVersions($informations->getApi());
        if(count($ambiguousVersions) > 0){
            return KnownTranslationFactory::pocketmine_plugin_ambiguousMinAPI(implode(", ", $ambiguousVersions));
        }
        return null;
    }

}