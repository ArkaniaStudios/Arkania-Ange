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
		$this->name        = $name;
		$this->description = $description;
		$this->chat        = $chat;
		$this->nameTag     = $nameTag;
		$this->color       = $color;
		$this->prefix      = $prefix;
		$this->priority    = $priority;
		$this->isDefault   = $isDefault;
		$this->isStaff     = $isStaff;
		$this->permissions = $permissions;
		$this->__constructPermission($name, $permissions);
		$this->__constructPersistence(Engine::getInstance()->getDataFolder() . 'ranks/');
	}

	public function getName() : string {
		return $this->name;
	}

	public function getDescription() : string {
		return $this->description;
	}

	public function getChatFormatter() : ChatFormatter {
		return $this->chat;
	}

	public function getNameTagFormatter() : NameTagFormatter {
		return $this->nameTag;
	}

	public function getColor() : string {
		return $this->color;
	}

	public function getPrefix() : string {
		return $this->prefix;
	}

	public function getPriority() : int {
		return $this->priority;
	}

	public function isDefault() : bool {
		return $this->isDefault;
	}

	public function isStaff() : bool {
		return $this->isStaff;
	}

}
