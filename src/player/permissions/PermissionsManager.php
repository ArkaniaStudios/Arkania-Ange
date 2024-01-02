<?php
declare(strict_types=1);

namespace arkania\player\permissions;

use arkania\events\permissions\RegisterPermissionEvent;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use InvalidArgumentException;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use ReflectionClass;
use UnitEnum;

class PermissionsManager implements NotOtherInstanceInterface {
    use NotOtherInstanceTrait {
        NotOtherInstanceTrait::__construct as private __notOtherInstanceConstruct;
    }

    private RegistryPermissionCache $registryPermissionCache;
    public function __construct() {
        $this->__notOtherInstanceConstruct();
    }

    public function getRegistryPermissionCache() : RegistryPermissionCache {
        return $this->registryPermissionCache ?? $this->registryPermissionCache = new RegistryPermissionCache();
    }

    public function registerPermission(Permission|string $permission, string $name = null) : void {
        $consoleRoot  = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_CONSOLE));
        $operatorRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_OPERATOR, '', [$consoleRoot]));
        if (is_string($permission)) {
            if ($name === null) {
                $name = str_replace('.', '_', $permission);
            }
            $permission = new Permission($permission);
        }
        $permissionsCache = $this->getRegistryPermissionCache();
        if (in_array($name, $permissionsCache->getPermissions(), true)) {
            return;
        }
        $ev = new RegisterPermissionEvent($permission);
        $ev->call();
        if($ev->isCancelled()) {
            return;
        }
        $permissionsCache->addPermission($name, $permission);
        DefaultPermissions::registerPermission($permission, [$operatorRoot]);
    }

    /**
     * @param UnitEnum[] $enums
     * @return void
     */
    public function registerEnumPermission(array $enums) : void {
        foreach ($enums as $enum) {
            if ($enum instanceof UnitEnum) {
                $this->registerPermission($enum->value, $enum->name);
            }
        }
    }

    public function registerPermissionClass(object $class) : void {
        $reflection = new ReflectionClass($class);
        foreach ($reflection->getConstants() as $name => $value) {
            if (is_string($value) && str_starts_with($name, 'PERMISSION_')) {
                $this->registerPermission($value, $name);
            }
        }
    }

    /**
     * @return Permission[]
     */
    public function getPermissions(): array {
        return $this->getRegistryPermissionCache()->getPermissions();
    }

    public function getPermission(string $name) : Permission {
        foreach ($this->getPermissions() as $permission) {
            if ($permission->getName() === $name) {
                return $permission;
            }
        }
        throw new InvalidArgumentException("Permission $name not found");
    }

}