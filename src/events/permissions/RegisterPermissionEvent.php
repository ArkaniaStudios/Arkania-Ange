<?php
declare(strict_types=1);

namespace arkania\events\permissions;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\permission\Permission;

class RegisterPermissionEvent extends Event implements Cancellable {
    use CancellableTrait;

    public function __construct(
        private readonly Permission $permission
    ) {}

    public function getPermission() : Permission {
        return $this->permission;
    }

}