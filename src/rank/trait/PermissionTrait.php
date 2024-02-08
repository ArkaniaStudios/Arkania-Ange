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

namespace arkania\rank\trait;

use pocketmine\permission\Permission;
use function array_search;

trait PermissionTrait {
	private string $name;

	/** @var string[]|Permission[]|null */
	protected array $permissions;

	/**
	 * @param string[]|Permission[]|null $permissions
	 */
	public function __construct(
		string $name,
		?array $permissions = null
	) {
		$this->name        = $name;
		$this->permissions = $permissions;
	}

	public function addPermission(Permission|string $permission) : void {
		if($permission instanceof Permission) {
			$permission = $permission->getName();
		}
		if (isset($this->permissions)) {
			return;
		}
		$this->permissions[] = $permission;
	}

	public function removePermission(Permission|string $permission) : void {
		if($permission instanceof Permission) {
			$permission = $permission->getName();
		}
		if (isset($this->permissions)) {
			return;
		}
		$key = array_search($permission, $this->permissions, true);
		if ($key === false) {
			return;
		}
		unset($this->permissions[$key]);
	}

	public function getPermissions() : ?array {
		return $this->permissions;
	}

}
