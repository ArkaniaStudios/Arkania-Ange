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

namespace arkania\item\components;

use arkania\item\BaseComponent;

class WearableComponent extends BaseComponent {
	public const SLOT_ARMOR           = "slot.armor";
	public const SLOT_ARMOR_CHEST     = "slot.armor.chest";
	public const SLOT_ARMOR_FEET      = "slot.armor.feet";
	public const SLOT_ARMOR_HEAD      = "slot.armor.head";
	public const SLOT_ARMOR_LEGS      = "slot.armor.legs";
	public const SLOT_CHEST           = "slot.chest";
	public const SLOT_ENDERCHEST      = "slot.enderchest";
	public const SLOT_EQUIPPABLE      = "slot.equippable";
	public const SLOT_HOTBAR          = "slot.hotbar";
	public const SLOT_INVENTORY       = "slot.inventory";
	public const SLOT_WEAPON_OFF_HAND = "slot.weapon.offhand";

	private string $slot;

	public function __construct(string $slot) {
		$this->slot = $slot;
	}

	public function getComponentName() : string {
		return "minecraft:wearable";
	}

	public function getValue() : array {
		return [
			"slot" => $this->slot
		];
	}

	public function isProperty() : bool {
		return false;
	}

}
