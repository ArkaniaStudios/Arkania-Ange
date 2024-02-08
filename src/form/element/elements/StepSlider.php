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

namespace arkania\form\element\elements;

class StepSlider extends Element {
	private string $text;
	private array $steps;
	private int $defaultStep;

	public function __construct(
		string $name,
		string $text,
		array $steps,
		int $defaultStep = 0
	) {
		parent::__construct($name);
		$this->text        = $text;
		$this->steps       = $steps;
		$this->defaultStep = $defaultStep;
	}

	public function getType() : string {
		return "step_slider";
	}

	public function handler($data) : bool|int|string {
		return $this->steps[$data];
	}

	public function jsonSerialize() : array {
		return [
			"type"    => $this->getType(),
			"text"    => $this->text,
			"steps"   => $this->steps,
			"default" => $this->defaultStep
		];
	}

}
