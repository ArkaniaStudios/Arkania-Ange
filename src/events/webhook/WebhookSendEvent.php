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

namespace arkania\events\webhook;

use arkania\webhook\class\Embed;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

class WebhookSendEvent extends Event implements Cancellable {
	use CancellableTrait;

	private string $webhookName;
	private string $type;
	private string $message;
	private ?Embed $embed;

	public function __construct(
		string $webhookName,
		string $type,
		string $message,
		Embed $embed = null
	) {
		$this->webhookName = $webhookName;
		$this->type        = $type;
		$this->message     = $message;
		$this->embed       = $embed;
	}

	public function getWebhookName() : string {
		return $this->webhookName;
	}

	public function getType() : string {
		return $this->type;
	}

	public function getMessage() : string {
		return $this->message;
	}

	public function setMessage(string $message) : void {
		$this->message = $message;
	}

	public function getEmbed() : ?Embed {
		return $this->embed;
	}

	public function setEmbed(Embed $embed) : void {
		$this->embed = $embed;
	}

}
