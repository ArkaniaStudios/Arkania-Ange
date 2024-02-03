<?php
declare(strict_types=1);

namespace arkania\rank\trait;

use pocketmine\permission\Permission;

trait PermissionTrait {

    private string $name;

    /** @var string[]|Permission[]|null */
    protected array $permissions;

    /**
     * @param string $name
     * @param string[]|Permission[]|null $permissions
     */
    public function __construct(
        string $name,
        ?array $permissions = null
    ) {
        $this->name = $name;
        $this->permissions = $permissions;
    }

    public function addPermission(string|Permission $permission) : void {
        if($permission instanceof Permission) {
            $permission = $permission->getName();
        }
        if (isset($this->permissions)) {
            return;
        }
        $this->permissions[] = $permission;
    }

    public function removePermission(string|Permission $permission) : void {
        if($permission instanceof Permission) {
            $permission = $permission->getName();
        }
        if (isset($this->permissions)) {
            return;
        }
        $key = array_search($permission, $this->permissions);
        if ($key === false) {
            return;
        }
        unset($this->permissions[$key]);
    }

    public function getPermissions() : ?array {
        return $this->permissions;
    }

}