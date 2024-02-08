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
use arkania\form\element\button\Button;
use arkania\lang\KnownTranslationsFactory;
use arkania\player\Session;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

class SimpleForm extends BaseForm {
	private string $content;
	/** @var Button[] */
	private array $buttons;
	/** @var ?callable */
	private $onSubmit;
	/** @var ?callable */
	private $onClose;

	public function __construct(
		Player $player,
		string|Translatable $title,
		string|Translatable $content,
		array $buttons,
		callable $onSubmit = null,
		callable $onClose = null
	) {
		parent::__construct($player, $title);
		$this->content  = $this->translate($content);
		$this->buttons  = $buttons;
		$this->onSubmit = $onSubmit;
		$this->onClose  = $onClose;
	}

	public function getType() : string {
		return "form";
	}

	public function handleResponse(Player $player, $data) : void {
		if($data === null) {
			if($this->onClose !== null) {
				($this->onClose)($player);
			}
		} else {
			if($this->onSubmit !== null) {
				$button = $this->buttons[$data];
				if(!$button->hasPermission()) {
					Session::get($player)->sendMessage(
						KnownTranslationsFactory::form_cant_use_button()
					);
				}
				($this->onSubmit)($player, $button->getName());
			}
		}
	}

	/**
	 * Méthode jsonSerialize de la classe SimpleForm.<br>
	 *
	 * Cette méthode est utilisée pour sérialiser l'objet SimpleForm en un tableau associatif qui peut être converti en JSON.<br. >
	 * Elle est généralement utilisée lorsque vous voulez envoyer l'objet SimpleForm à une API ou le stocker dans une base de données.<br>
	 *
	 * @return array<string, Button[]|string> Un tableau associatif avec les clés suivantes :<br>
	 *      - "type" : une chaîne de caractères représentant le type de formulaire. Dans ce cas, il s'agit toujours de "form".<br>
	 *      - "title" : une chaîne de caractères représentant le titre du formulaire. Il est défini lors de la construction de l'objet SimpleForm.<br>
	 *      - "content" : une chaîne de caractères représentant le contenu du formulaire. Il est défini lors de la construction de l'objet SimpleForm.<br>
	 *      - "buttons" : un tableau de boutons qui seront affichés sur le formulaire. Chaque bouton est une instance de la classe Button.<br>
	 */
	public function jsonSerialize() : array {
		return [
			"type"        => "form",
			"title"       => $this->title,
			"content"     => $this->content,
			"buttons"     => $this->buttons,
			"permissions" => $this->getPermissions()
		];
	}

}
