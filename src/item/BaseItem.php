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

namespace arkania\item;

use arkania\item\components\ComponentsTrait;
use arkania\item\creative\CreativeCategoryInfo;
use Exception;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\nbt\tag\CompoundTag;

abstract class BaseItem extends Item {
	use ComponentsTrait;

	private string $texture;
	private string $creativeInventoryInfo;
	private string $creativeGroup;

	public function __construct(
		ItemIdentifier $identifier,
		string $name = "Unknown",
		string $texture = 'stick',
		string $creativeInventoryInfo = CreativeCategoryInfo::CATEGORY_ALL,
		string $creativeGroup = '',
		array $enchantmentTags = []
	) {
		parent::__construct($identifier, $name, $enchantmentTags);
		$this->texture               = $texture;
		$this->creativeInventoryInfo = $creativeInventoryInfo;
		$this->creativeGroup         = $creativeGroup;
		foreach ($this->getComponents() as $component) {
			$this->addComponent($component);
		}
	}

	/**
	 * @return BaseComponent[]
	 */
	abstract public function getComponents() : array;

	final public function getCompoundTag() : CompoundTag {
		$components = CompoundTag::create();
		$properties = CompoundTag::create();
		$properties->setInt("creative_category", $this->convertCreativeInfoToInt());
		$properties->setString("creative_group", $this->creativeGroup);
		$properties->setTag(
			"minecraft:icon",
			CompoundTag::create()
				->setString("texture", $this->texture)
		);
		foreach($this->components as $component) {
			$tag = ItemManager::getTagType($component->getValue());
			if ($tag === null) {
				throw new Exception("Failed to get tag type for component " . $component->getComponentName());
			}
			if ($component->isProperty()) {
				$properties->setTag($component->getComponentName(), $tag);
				continue;
			}
			$components->setTag($component->getComponentName(), $tag);
		}
		$components->setTag(
			"item_properties",
			$properties
		);
		return CompoundTag::create()
			->setTag("components", $components)
			->setInt("id", $this->getTypeId())
			->setString("name", $this->getName());
	}

	final public function getCreativeInventoryInfo() : string {
		return $this->creativeInventoryInfo;
	}

	private function convertCreativeInfoToInt() : int {
		return match ($this->getCreativeInventoryInfo()) {
			CreativeCategoryInfo::CATEGORY_ALL          => 0,
			CreativeCategoryInfo::CATEGORY_CONSTRUCTION => 1,
			CreativeCategoryInfo::CATEGORY_NATURE       => 2,
			CreativeCategoryInfo::CATEGORY_EQUIPMENT    => 3,
			CreativeCategoryInfo::CATEGORY_ITEMS        => 4,
			CreativeCategoryInfo::CATEGORY_COMMANDS     => 5
		};
	}

}
