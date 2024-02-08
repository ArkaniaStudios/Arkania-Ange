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

/**
 *     _      ____    _  __     _      _   _   ___      _             __     __  ____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            \ \   / / |___ \
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____   \ \ / /    __) |
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____|   \ V /    / __/
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            \_/    |_____|
 *
 * @author: Julien
 * @link: https://github.com/ArkaniaStudios
 */

namespace arkania\npc\base;

use arkania\commands\default\NpcCommand;
use arkania\npc\FormManager;
use arkania\npc\NpcDataIds;
use arkania\npc\NpcTrait;
use arkania\player\permissions\PermissionsBase;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use function is_null;

class CustomEntity extends Human {
	use NpcTrait;
	public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null) {
		parent::__construct($location, $skin, $nbt);
		if (!is_null($nbt) && $nbt->getTag(NpcDataIds::ENTITY_NPC) !== null) {
			$this->restorNpcData($nbt);
			$this->setScale($this->getTaille());
		}
		$this->setNameTagAlwaysVisible();
	}

	public function saveNBT() : CompoundTag {
		$nbt = parent::saveNBT();
		if ($this->isNpc()) {
			$nbt = $this->saveNpcData($nbt);
		}
		return $nbt;
	}

	public function attack(EntityDamageEvent $source) : void {
		if (!$this->isNpc()) {
			parent::attack($source);
		} elseif($source instanceof EntityDamageByEntityEvent) {
			$player = $source->getDamager();
			if ($player instanceof Player) {
				if($player->hasPermission(PermissionsBase::getPermission('npc')) || $player->getServer()->isOp($player->getName())) {
					if (isset(NpcCommand::$npc[$player->getName()])) {
						if (NpcCommand::$npc[$player->getName()] === 'disband') {
							$this->flagForDespawn();
							if (!$player->isSneaking()) {
								$player->sendMessage('npc.delete.success');
								unset(NpcCommand::$npc[$player->getName()]);
							} else {
								$player->sendMessage('npc.delete.success');
							}
						} elseif(NpcCommand::$npc[$player->getName()] === 'rotate') {
							FormManager::getInstance()->sendNpcChangePositionForm($player, $this);
							unset(NpcCommand::$npc[$player->getName()]);
						} elseif(NpcCommand::$npc[$player->getName()] === 'edit') {
							FormManager::getInstance()->sendNpcWithItemForm($player, $this);
							unset(NpcCommand::$npc[$player->getName()]);
						}
					}
					if ($player->getInventory()->getItemInHand()->getTypeId() === VanillaItems::RECORD_STRAD()->getTypeId()) {
						FormManager::getInstance()->sendNpcWithItemForm($player, $this);
					} else {
						$this->executeCommand($player);
					}
				} else {
					$this->executeCommand($player);
				}
			}
		}
	}
}
