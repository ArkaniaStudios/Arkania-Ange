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

namespace arkania\item\default;

use arkania\item\BaseItem;
use arkania\item\components\AllowOffHandComponent;
use arkania\item\components\CanDestroyInCreativeComponent;
use arkania\item\components\DisplayNameComponent;
use arkania\item\components\HandEquippedComponent;
use arkania\item\components\MaxStackSizeComponent;
use arkania\item\components\WearableComponent;
use arkania\item\creative\CreativeCategoryInfo;
use pocketmine\item\ItemIdentifier;

class ItemTest extends BaseItem {
	public function __construct(
		ItemIdentifier $identifier,
		string $name = "Unknown",
		string $texture = 'stick',
		string $creativeInventoryInfo = CreativeCategoryInfo::CATEGORY_ALL,
		string $creativeGroup = '',
		array $enchantmentTags = []
	) {
		parent::__construct($identifier, $name, $texture, $creativeInventoryInfo, $creativeGroup, $enchantmentTags);
	}

	public function getComponents() : array {
		return [
			new MaxStackSizeComponent(),
			new CanDestroyInCreativeComponent(true),
			new DisplayNameComponent('Item Test'),
			new WearableComponent(WearableComponent::SLOT_WEAPON_OFF_HAND),
			new HandEquippedComponent(true),
			new AllowOffHandComponent(true)
		];
	}

}
