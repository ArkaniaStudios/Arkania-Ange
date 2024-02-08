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

namespace arkania\webhook\class;

use JsonSerializable;

final class Message implements JsonSerializable {
	private array $data = [];

	public function setContent(string $content) : void {
		$this->data['content'] = $content;
	}

	public function setName(string $name) : void {
		$this->data['username'] = $name;
	}

	public function setAvatar(string $url) : void {
		$this->data['avatar_url'] = $url;
	}

	public function setTts(bool $tts) : void {
		$this->data['tts'] = $tts;
	}

	public function addEmbed(?Embed $embed) : void {
		if ($embed === null) {
			return;
		}
		$this->data['embeds'][] = $embed->__toArray();
	}

	public function jsonSerialize() : array {
		return $this->data;
	}

}
