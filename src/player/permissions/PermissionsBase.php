<?php
declare(strict_types=1);

namespace arkania\player\permissions;

enum PermissionsBase : string {

    case PERMISSION_BASE = "arkania.permission.base";
    case PERMISSION_MAINTENANCE = "arkania.permission.maintenance";

    /**
     * @throws MissingPermissionException
     */
    public static function getPermission(string $permissionName) : string {
        return match ($permissionName) {
            'base' => self::PERMISSION_BASE->value,
            'maintenance' => self::PERMISSION_MAINTENANCE->value,
            default => throw new MissingPermissionException("Permission $permissionName not found")
        };
    }
}