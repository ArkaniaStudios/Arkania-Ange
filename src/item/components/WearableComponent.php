<?php
declare(strict_types=1);

namespace arkania\item\components;

use arkania\item\BaseComponent;

class WearableComponent extends BaseComponent {

    public const SLOT_ARMOR = "slot.armor";
    public const SLOT_ARMOR_CHEST = "slot.armor.chest";
    public const SLOT_ARMOR_FEET = "slot.armor.feet";
    public const SLOT_ARMOR_HEAD = "slot.armor.head";
    public const SLOT_ARMOR_LEGS = "slot.armor.legs";
    public const SLOT_CHEST = "slot.chest";
    public const SLOT_ENDERCHEST = "slot.enderchest";
    public const SLOT_EQUIPPABLE = "slot.equippable";
    public const SLOT_HOTBAR = "slot.hotbar";
    public const SLOT_INVENTORY = "slot.inventory";
    public const SLOT_WEAPON_OFF_HAND = "slot.weapon.offhand";

    private string $slot;

    public function __construct(string $slot) {
        $this->slot = $slot;
    }

    public function getComponentName(): string {
        return "minecraft:wearable";
    }

    public function getValue(): array {
        return [
            "slot" => $this->slot
        ];
    }

    public function isProperty(): bool {
        return false;
    }

}