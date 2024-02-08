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
 *     _      ____    _  __     _      _   _   ___      _                   _      ____    ___
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \                 / \    |  _ \  |_ _|
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____      / _ \   | |_) |  | |
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____|    / ___ \  |  __/   | |
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\           /_/   \_\ |_|     |___|
 *
 * @author: Julien
 * @link: https://github.com/ArkaniaStudios
 */

namespace arkania\npc\type\customs;

use arkania\npc\base\SimpleEntity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use function str_replace;

class FloatingText extends SimpleEntity {
	public float $gravity = 0.0;

	protected function getInitialSizeInfo() : EntitySizeInfo {
		return new EntitySizeInfo(0.5, 0.7);
	}
	public static function getNetworkTypeId() : string {
		return EntityIds::FALLING_BLOCK;
	}

	public function getName() : string {
		return 'floatingText';
	}

	public function spawnTo(Player $player) : void {
		$this->setNpc();
		$this->setNameTagAlwaysVisible();
		$this->setNameTag(str_replace('{LINE}', "\n", $this->getCustomName()));
		parent::spawnTo($player);
	}
}
