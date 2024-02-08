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

namespace arkania\form;

use arkania\form\permission\FormPermissionTrait;
use arkania\form\translation\FormTranslationTrait;
use pocketmine\form\Form;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

abstract class BaseForm implements Form {
	use FormTranslationTrait {
		FormTranslationTrait::__construct as private __formTranslationConstruct;
	}
	use FormPermissionTrait{
		FormPermissionTrait::__construct as private __formPermissionConstruct;
	}

	protected string $title;

	public function __construct(
		Player $player,
		string|Translatable $title
	) {
		$this->__formTranslationConstruct($player);
		$this->__formPermissionConstruct($player);
		$this->player = $player;
		$this->title  = $this->translate($title);
	}

	public function getTitle() : string {
		return $this->title;
	}

	abstract public function getType() : string;

}
