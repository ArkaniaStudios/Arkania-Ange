<?php
declare(strict_types=1);

namespace arkania\rank\class;

use arkania\Engine;
use arkania\rank\permissions\ManagePermissions;
use arkania\rank\trait\PermissionTrait;
use arkania\rank\trait\RankPersistenceTrait;

final class Rank extends ManagePermissions {
    use PermissionTrait {
        PermissionTrait::__construct as private __constructPermission;
    }
    use RankPersistenceTrait {
        RankPersistenceTrait::__construct as private __constructPersistence;
    }

    private string $name;
    private string $description;
    private ChatFormatter $chat;
    private NameTagFormatter $nameTag;
    private string $color;
    private string $prefix;
    private int $priority;
    private bool $isDefault;
    private bool $isStaff;

    public function __construct(
        string $name,
        string $description,
        ChatFormatter $chat,
        NameTagFormatter $nameTag,
        string $color,
        string $prefix,
        int $priority,
        bool $isDefault,
        bool $isStaff,
        ?array $permissions = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->chat = $chat;
        $this->nameTag = $nameTag;
        $this->color = $color;
        $this->prefix = $prefix;
        $this->priority = $priority;
        $this->isDefault = $isDefault;
        $this->isStaff = $isStaff;
        $this->permissions = $permissions;
        $this->__constructPermission($name, $permissions);
        $this->__constructPersistence(Engine::getInstance()->getDataFolder() . 'ranks/');
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getChatFormatter(): ChatFormatter {
        return $this->chat;
    }

    public function getNameTagFormatter(): NameTagFormatter {
        return $this->nameTag;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getPrefix(): string {
        return $this->prefix;
    }

    public function getPriority(): int {
        return $this->priority;
    }

    public function isDefault(): bool {
        return $this->isDefault;
    }

    public function isStaff(): bool {
        return $this->isStaff;
    }

}