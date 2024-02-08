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

namespace arkania\form\element\button;

use arkania\form\permission\FormPermissionTrait;
use arkania\form\translation\FormTranslationTrait;
use JsonSerializable;
use pocketmine\lang\Translatable;

class Button implements JsonSerializable {
	use FormTranslationTrait;
	use FormPermissionTrait;

	private string $name;
	private string|Translatable $text;
	private ?IconUrl $icon;

	public function __construct(
		string $name,
		string|Translatable $text,
		string $permission = null,
		?IconUrl $icon = null
	) {
		$this->name = $name;
		$this->text = $this->translate($text);
		$this->icon = $icon;
		if($permission !== null) {
			$this->setPermission($permission);
		}
	}

	public function getName() : string {
		return $this->name;
	}

	/**
	 * @return array<string,string|string[]>
	 */
	public function jsonSerialize() : array {
		return [
			'text'  => $this->text,
			'image' => $this->icon?->jsonSerialize() ?? null
		];
	}

}
