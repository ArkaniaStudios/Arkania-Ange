<?php
declare(strict_types=1);

namespace arkania\item;

use arkania\item\default\ItemTest;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\utils\CloningRegistryTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-anotation.php
 * @generate-registry-docblock
 *
 * @method static ItemTest ITEM_TEST()
 */
class ExtraItems {
    use CloningRegistryTrait;

    protected static function register(string $name, Item $item) : void {
        self::_registryRegister($name, $item);
    }

    /**
     * @return Item[]
     */
    public static function getAll() : array {
        /** @var Item[] */
        return self::_registryGetAll();
    }

    protected static function setup() : void {
        self::register('item_test', new ItemTest(new ItemIdentifier(ItemTypeIds::newId()), "Item Test"));
    }

}