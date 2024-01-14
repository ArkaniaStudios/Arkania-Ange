<?php
declare(strict_types=1);

namespace arkania\player\permissions;

use pocketmine\permission\Permission;

class RegistryPermissionCache {

    /** @var array<string, Permission>|null */
    private ?array $permissions = null;

    public function addPermission(string $name, Permission $permission) : void {
        $this->permissions[$name] = $permission;
    }

    /**
     * @return Permission[]
     */
    public function getPermissions() : array {
        return $this->permissions ?? [];
    }

}