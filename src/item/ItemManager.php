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

use arkania\item\creative\ItemTypesNames;
use Exception;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\Tag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use ReflectionClass;
use ReflectionException;
use function array_keys;
use function array_map;
use function count;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function range;

class ItemManager {
	public function __construct(

	) {
		$this->registerItem(
			ItemTypesNames::ITEM_TEST,
			ExtraItems::ITEM_TEST(),
			[
				ItemTypesNames::ITEM_TEST,
				'item_test'
			]
		);
	}

	/** @var (ItemTypeEntry|mixed)[] */
	private array $componentsEntries = [];
	/** @var ItemTypeEntry[] */
	private array $itemsEntries = [];

	public function registerItem(string $id, BaseItem $item, array $stringToItemParserNames) : void {
		GlobalItemDataHandlers::getDeserializer()->map($id, fn () => clone $item);
		GlobalItemDataHandlers::getSerializer()->map($item, fn () => new SavedItemData($id));
		foreach ($stringToItemParserNames as $name) {
			StringToItemParser::getInstance()->register($name, fn () => clone $item);
		}
		$this->registerCustomItemMapping($id, $item->getTypeId());
		$this->registerCustomItemPacketsCache($id, $item);
		CreativeInventory::getInstance()->add($item);
	}

	/**
	 * @throws ReflectionException
	 */
	private function registerCustomItemMapping(string $id, int $itemTypeId) : void {
		$dictionary = TypeConverter::getInstance()->getItemTypeDictionary();
		$reflection = new ReflectionClass($dictionary);
		$properties = [
			["intToStringIdMap", [$itemTypeId => $id]],
			["stringToIntMap", [$id => $itemTypeId]]
		];

		foreach ($properties as $data) {
			$property = $reflection->getProperty($data[0]);
			$property->setValue($dictionary, $property->getValue($dictionary) + $data[1]);
		}
	}

	/**
	 * @throws Exception
	 */
	private function registerCustomItemPacketsCache(string $id, BaseItem $item) : void {
		$this->componentsEntries[] = new ItemComponentPacketEntry($id, new CacheableNbt($item->getCompoundTag()));
		$this->itemsEntries[]      = new ItemTypeEntry($id, $item->getTypeId(), true);
	}

	/**
	 * @return ItemTypeEntry[]
	 */
	public function getItemsEntries() : array {
		return $this->itemsEntries;
	}

	/**
	 * @return ItemComponentPacketEntry[]
	 */
	public function getComponentsEntries() : array {
		return $this->componentsEntries;
	}

	public static function getTagType($type) : ?Tag {
		return match (true) {
			is_array($type)              => self::getArrayTag($type),
			is_bool($type)               => new ByteTag($type ? 1 : 0),
			is_float($type)              => new FloatTag($type),
			is_int($type)                => new IntTag($type),
			is_string($type)             => new StringTag($type),
			$type instanceof CompoundTag => $type,
			default                      => null,
		};
	}

	private static function getArrayTag(array $array) : Tag {
		if(array_keys($array) === range(0, count($array) - 1)) {
			return new ListTag(array_map(fn ($value) => self::getTagType($value), $array));
		}
		$tag = CompoundTag::create();
		foreach($array as $key => $value) {
			$tag->setTag($key, self::getTagType($value));
		}
		return $tag;
	}

}
