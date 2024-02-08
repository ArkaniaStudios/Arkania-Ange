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

class Slider extends Element {
	private string $text;
	private int $min;
	private int $max;
	private int $step;
	private int $default;

	public function __construct(
		string $name,
		string $text,
		int $min = 0,
		int $max = 100,
		int $step = 1,
		int $default = 0
	) {
		parent::__construct($name);
		$this->text    = $text;
		$this->min     = $min;
		$this->max     = $max;
		$this->step    = $step;
		$this->default = $default;
	}

	public function getType() : string {
		return "slider";
	}

	public function handler($data) : bool|int|string {
		return $data;
	}

	public function jsonSerialize() : array {
		return [
			"type"    => $this->getType(),
			"text"    => $this->text,
			"min"     => $this->min,
			"max"     => $this->max,
			"step"    => $this->step,
			"default" => $this->default
		];
	}

}
