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

namespace arkania\utils;

use arkania\npc\base\CustomEntity;
use arkania\npc\base\SimpleEntity;
use arkania\npc\type\agressives\Blaze;
use arkania\npc\type\agressives\CaveSpider;
use arkania\npc\type\agressives\Creeper;
use arkania\npc\type\agressives\Drowned;
use arkania\npc\type\agressives\ElderGuardian;
use arkania\npc\type\agressives\EnderDragon;
use arkania\npc\type\agressives\Enderman;
use arkania\npc\type\agressives\Endermite;
use arkania\npc\type\agressives\EvocationFang;
use arkania\npc\type\agressives\EvocationIllager;
use arkania\npc\type\agressives\Ghast;
use arkania\npc\type\agressives\Guardian;
use arkania\npc\type\agressives\Hoglin;
use arkania\npc\type\agressives\Husk;
use arkania\npc\type\agressives\MagmaCube;
use arkania\npc\type\agressives\Phantom;
use arkania\npc\type\agressives\Piglin;
use arkania\npc\type\agressives\Pillager;
use arkania\npc\type\agressives\Ravager;
use arkania\npc\type\agressives\Shulker;
use arkania\npc\type\agressives\Silverfish;
use arkania\npc\type\agressives\Skeleton;
use arkania\npc\type\agressives\Slime;
use arkania\npc\type\agressives\Spider;
use arkania\npc\type\agressives\Stray;
use arkania\npc\type\agressives\Vex;
use arkania\npc\type\agressives\Vindicator;
use arkania\npc\type\agressives\Warden;
use arkania\npc\type\agressives\Witch;
use arkania\npc\type\agressives\Wither;
use arkania\npc\type\agressives\WitherSkeleton;
use arkania\npc\type\agressives\Zoglin;
use arkania\npc\type\agressives\Zombie;
use arkania\npc\type\agressives\ZombieVillager;
use arkania\npc\type\customs\Ballon;
use arkania\npc\type\customs\FloatingText;
use arkania\npc\type\customs\Piniata;
use arkania\npc\type\HumanEntity;
use arkania\npc\type\neutral\Bat;
use arkania\npc\type\neutral\Bee;
use arkania\npc\type\neutral\Dolphin;
use arkania\npc\type\neutral\Fox;
use arkania\npc\type\neutral\Goat;
use arkania\npc\type\neutral\IronGolem;
use arkania\npc\type\neutral\Llama;
use arkania\npc\type\neutral\PolarBear;
use arkania\npc\type\neutral\SkeletonHorse;
use arkania\npc\type\neutral\SnowGolem;
use arkania\npc\type\neutral\Wolf;
use arkania\npc\type\neutral\ZombieHorse;
use arkania\npc\type\neutral\ZombifiedPiglin;
use arkania\npc\type\passives\Axolotl;
use arkania\npc\type\passives\Cat;
use arkania\npc\type\passives\Chicken;
use arkania\npc\type\passives\Cod;
use arkania\npc\type\passives\Cow;
use arkania\npc\type\passives\Donkey;
use arkania\npc\type\passives\Frog;
use arkania\npc\type\passives\GlowSquid;
use arkania\npc\type\passives\Horse;
use arkania\npc\type\passives\Mule;
use arkania\npc\type\passives\Ocelot;
use arkania\npc\type\passives\Panda;
use arkania\npc\type\passives\Parrot;
use arkania\npc\type\passives\Pig;
use arkania\npc\type\passives\Rabbit;
use arkania\npc\type\passives\Salmon;
use arkania\npc\type\passives\Sheep;
use arkania\npc\type\passives\Squid;
use arkania\npc\type\passives\Strider;
use arkania\npc\type\passives\Tadpole;
use arkania\npc\type\passives\TropicalFish;
use arkania\npc\type\passives\Turtle;
use arkania\npc\type\passives\Villager;
use arkania\npc\type\passives\WanderingTrader;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\World;
use function is_null;
use function strtolower;

class Loader {
	public function __construct() {
		$this->initEntity();
	}

	/** @var SimpleEntity|CustomEntity[] */
	public static array $entities = [];

	private static array $customNamespaces = [];

	private function initEntity() : void {
		$this->register(HumanEntity::class, ['arkania:human', 'human']);
		$this->register(Cow::class, ['arkania:cow', 'cow', 'vache'], EntityIds::COW);
		$this->register(Villager::class, ['arkania:villager', 'villageois', 'villager'], EntityIds::VILLAGER);
		$this->register(Pig::class, ['arkania:pig', 'pig', 'cochon'], EntityIds::PIG);
		$this->register(Chicken::class, ['arkania:chicken', 'chicken', 'poulet'], EntityIds::CHICKEN);
		$this->register(Sheep::class, ['arkania:sheep', 'sheep', 'mouton'], EntityIds::SHEEP);
		$this->register(Skeleton::class, ['arkania:skeleton', 'skeleton', 'squelette'], EntityIds::SKELETON);
		$this->register(Zombie::class, ['arkania:zombie', 'zombie'], EntityIds::ZOMBIE);
		$this->register(Enderman::class, ['arkania:enderman', 'enderman'], EntityIds::ENDERMAN);
		$this->register(Wither::class, ['arkania:wither', 'wither'], EntityIds::WITHER);
		$this->register(Slime::class, ['arkania:slime', 'slime'], EntityIds::SLIME);
		$this->register(Creeper::class, ['arkania:creeper', 'creeper'], EntityIds::CREEPER);
		$this->register(Horse::class, ['arkania:horse', 'horse', 'cheval'], EntityIds::HORSE);
		$this->register(Axolotl::class, ['arkania:axolotl', 'axolotl']);
		$this->register(Blaze::class, ['arkania:blaze', 'blaze'], EntityIds::BLAZE);
		$this->register(CaveSpider::class, ['arkania:cavespider', 'cavespider', 'cspider'], EntityIds::CAVE_SPIDER);
		$this->register(Drowned::class, ['arkania:drowned', 'droned'], EntityIds::DROWNED);
		$this->register(ElderGuardian::class, ['arkania:elderguardian', 'elderguardian'], EntityIds::ELDER_GUARDIAN);
		$this->register(EnderDragon::class, ['arkania:enderdragon', 'enderdragon'], EntityIds::ENDER_DRAGON);
		$this->register(Endermite::class, ['arkania:endermite', 'endermite'], EntityIds::ENDERMITE);
		$this->register(EvocationFang::class, ['arkania:evocationf', 'evocationf'], EntityIds::EVOCATION_FANG);
		$this->register(EvocationIllager::class, ['arkania:evocationi', 'evocationi'], EntityIds::EVOCATION_ILLAGER);
		$this->register(Ghast::class, ['arkania:ghast', 'ghast'], EntityIds::GHAST);
		$this->register(Guardian::class, ['arkania:guardian', 'guardian', 'gardien'], EntityIds::GUARDIAN);
		$this->register(Hoglin::class, ['arkania:hoglin', 'hoglin']);
		$this->register(Husk::class, ['arkania:husk', 'husk'], EntityIds::HUSK);
		$this->register(MagmaCube::class, ['arkania:magmacube', 'magmacube'], EntityIds::MAGMA_CUBE);
		$this->register(Phantom::class, ['arkania:phantom', 'phantom'], EntityIds::PHANTOM);
		$this->register(Piglin::class, ['arkania:piglin', 'piglin']);
		$this->register(Pillager::class, ['arkania:pillager', 'pillager']);
		$this->register(Ravager::class, ['arkania:ravager', 'ravager']);
		$this->register(Shulker::class, ['arkania:shulker', 'shulker'], EntityIds::SHULKER);
		$this->register(Silverfish::class, ['arkania:silverfish', 'silverfish'], EntityIds::SILVERFISH);
		$this->register(Spider::class, ['arkania:spider', 'spider', 'arraignee'], EntityIds::SPIDER);
		$this->register(Stray::class, ['arkania:stray', 'stray'], EntityIds::STRAY);
		$this->register(Vex::class, ['arkania:vex', 'vex'], EntityIds::VEX);
		$this->register(Vindicator::class, ['arkania:vindicator', 'vindicator'], EntityIds::VINDICATOR);
		$this->register(Warden::class, ['arkania:warden', 'warden']);
		$this->register(Witch::class, ['arkania:witch', 'witch', 'sorciere'], EntityIds::WITCH);
		$this->register(WitherSkeleton::class, ['arkania:witherskeleton', 'witherskeleton', 'ws'], EntityIds::WITHER_SKELETON);
		$this->register(Zoglin::class, ['arkania:zoglin', 'zoglin']);
		$this->register(ZombieVillager::class, ['arkania:zombievillager', 'zombievillager'], EntityIds::ZOMBIE_VILLAGER);
		$this->register(Bat::class, ['arkania:bat', 'bat'], EntityIds::BAT);
		$this->register(Bee::class, ['arkania:bee', 'bee']);
		$this->register(Dolphin::class, ['arkania:dolphin', 'dolphin'], EntityIds::DOLPHIN);
		$this->register(Fox::class, ['arkania:fox', 'fox']);
		$this->register(Goat::class, ['arkania:goat', 'goat']);
		$this->register(IronGolem::class, ['arkania:irongolem', 'irongolem'], EntityIds::IRON_GOLEM);
		$this->register(Llama::class, ['arkania:llama', 'llama'], EntityIds::LLAMA);
		$this->register(PolarBear::class, ['arkania:polarbear', 'polarbear'], EntityIds::POLAR_BEAR);
		$this->register(SkeletonHorse::class, ['arkania:skeletonhorse', 'skeletonhorse'], EntityIds::SKELETON_HORSE);
		$this->register(SnowGolem::class, ['arkania:snowgolem', 'snowgolem'], EntityIds::SNOW_GOLEM);
		$this->register(Wolf::class, ['arkania:wolf', 'wolf'], EntityIds::WOLF);
		$this->register(ZombieHorse::class, ['arkania:zombiehorse', 'zombiehorse'], EntityIds::ZOMBIE_HORSE);
		$this->register(ZombifiedPiglin::class, ['arkania:zombiefiedpiglin', 'zpiglin'], EntityIds::ZOMBIE_PIGMAN);
		$this->register(Cat::class, ['arkania:cat', 'cat'], EntityIds::CAT);
		$this->register(Cod::class, ['arkania:cod', 'cod'], EntityIds::COD);
		$this->register(Donkey::class, ['arkania:donkey', 'donkey'], EntityIds::DONKEY);
		$this->register(Frog::class, ['arkania:frog', 'frog']);
		$this->register(GlowSquid::class, ['arkania:glowsquid', 'glowsquid']);
		$this->register(Mule::class, ['arkania:mule', 'mule'], EntityIds::MULE);
		$this->register(Ocelot::class, ['arkania:ocelot', 'ocelot'], EntityIds::OCELOT);
		$this->register(Panda::class, ['arkania:panda', 'panda'], EntityIds::PANDA);
		$this->register(Parrot::class, ['arkania:parrot', 'parrot'], EntityIds::PARROT);
		$this->register(Rabbit::class, ['arkania:rabbit', 'rabbit', 'lapin'], EntityIds::RABBIT);
		$this->register(Salmon::class, ['arkania:salmon', 'salmon'], EntityIds::SALMON);
		$this->register(Squid::class, ['arkania:squid', 'squid'], EntityIds::SQUID);
		$this->register(Strider::class, ['arkania:strider', 'strider']);
		$this->register(Tadpole::class, ['arkania:tadpole', 'tadpole']);
		$this->register(TropicalFish::class, ['arkania:tropicalfish', 'tropicalfish'], EntityIds::TROPICALFISH);
		$this->register(Turtle::class, ['arkania:turtle', 'turtle'], EntityIds::TURTLE);
		$this->register(WanderingTrader::class, ['arkania:wanderingtrader', 'wanderingtrader']);
		$this->register(Ballon::class, ['arkania:balloon', 'ballon'], EntityIds::BALLOON);
		$this->register(FloatingText::class, ['arkania:floatingtext', 'floatingtext'], EntityIds::FALLING_BLOCK, "arkania:floatingtext");
		$this->register(Piniata::class, ['arkania:piniata', 'piniata'], EntityIds::LLAMA, "arkania:piniata");
	}

	private function register(string $classEntity, array $names, null|int|string $entityId = null, ?string $customNamespace = null) : void {
		foreach ($names as $name) {
			self::$entities[strtolower($name)] = $classEntity;
		}
		EntityFactory::getInstance()->register($classEntity, function (World $world, CompoundTag $nbt) use ($classEntity, $names) : Entity {
			if($classEntity === HumanEntity::class) {
				return new $classEntity(EntityDataHelper::parseLocation($nbt, $world), HumanEntity::parseSkinNBT($nbt), $nbt);
			}
			return new $classEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt);
		}, $names, $entityId);
		if(!is_null($customNamespace)) {
			self::$customNamespaces[] = $customNamespace;
		}
	}

	public static function getCustomNamespaces() : array {
		return self::$customNamespaces;
	}

}
