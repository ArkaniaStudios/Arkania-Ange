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

namespace arkania\form\class;

use arkania\form\BaseForm;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

class ModalForm extends BaseForm {
	private string $content;
	private string $button1;
	private string $button2;
	/** @var ?callable */
	private $onSubmit;
	/** @var ?callable */
	private $onClose;

	public function __construct(
		Player $player,
		string|Translatable $title,
		string|Translatable $content,
		string|Translatable $button1,
		string|Translatable $button2,
		?callable $onSubmit = null,
		?callable $onClose = null
	) {
		parent::__construct($player, $title);
		$this->content  = $this->translate($content);
		$this->button1  = $this->translate($button1);
		$this->button2  = $this->translate($button2);
		$this->onSubmit = $onSubmit;
		$this->onClose  = $onClose;
	}

	public function getType() : string {
		return "modal";
	}

	public function handleResponse(Player $player, $data) : void {
		if($data === null) {
			if($this->onClose !== null) {
				($this->onClose)($player);
			}
		} else {
			if($this->onSubmit !== null) {
				($this->onSubmit)($player, $data);
			}
		}
	}

	/**
	 * @return string[]
	 */
	public function jsonSerialize() : array {
		return [
			"type"        => $this->getType(),
			"title"       => $this->title,
			"content"     => $this->content,
			"button1"     => $this->button1,
			"button2"     => $this->button2,
			"permissions" => $this->getPermissions()
		];
	}

}
