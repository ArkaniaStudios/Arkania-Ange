<?php
declare(strict_types=1);

namespace arkania\form\permission;

use pocketmine\player\Player;

trait FormPermissionTrait {

    private ?string $permissions = null;

    public function __construct(
        Player $player
    ) {}

    public function getPermissions(): string {
        return $this->permissions;
    }

    public function setPermission(string $permission) : void {
        $this->permissions = $permission;
    }

    public function hasPermission() : bool {
        if($this->permissions === null){
            return true;
        }
        return $this->player->hasPermission($this->permissions);
    }

}