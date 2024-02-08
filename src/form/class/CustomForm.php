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
use arkania\form\element\elements\Element;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

class CustomForm extends BaseForm {
	/** @var Element[] */
	private array $elements;
	/** @var ?callable */
	private $onSubmit;
	/** @var ?callable */
	private $onClose;

	public function __construct(
		Player $player,
		string|Translatable $title,
		array $elements = [],
		?callable $onSubmit = null,
		?callable $onClose = null
	) {
		parent::__construct($player, $title);
		$this->elements = $elements;
		$this->onSubmit = $onSubmit;
		$this->onClose  = $onClose;
	}

	public function getType() : string {
		return "custom_form";
	}

	public function handleResponse(Player $player, $data) : void {
		if ($data === null) {
			if ($this->onClose !== null) {
				($this->onClose)($player);
			}
			return;
		}
		$count = [];
		foreach ($data as $key => $value) {
			$element     = $this->elements[$key];
			$elementName = $element->getName();
			if (isset($data[$elementName])) {
				$count[$elementName]++;
				$data[$elementName . '-' . $count[$elementName]] = $element->handle($value);
			} else {
				$count[$elementName] = 1;
				$data[$elementName]  = $element->handler($value);
			}
			unset($data[$key]);
		}

		unset($this->labels);
		if ($this->onSubmit !== null) {
			($this->onSubmit)($player, $data);
		}
	}

	public function jsonSerialize() : array {
		return [
			"type"        => $this->getType(),
			"title"       => $this->getTitle(),
			"content"     => $this->elements,
			"permissions" => $this->getPermissions()
		];
	}

}
