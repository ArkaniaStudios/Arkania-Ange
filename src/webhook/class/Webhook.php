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

use arkania\Engine;
use arkania\events\webhook\WebhookSendEvent;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use arkania\webhook\thread\WebhookThread;
use function json_encode;

abstract class Webhook implements NotOtherInstanceInterface {
	use NotOtherInstanceTrait {
		NotOtherInstanceTrait::__construct as private __notOtherInstanceConstruct;
	}

	private Engine $engine;
	private string $url;
	private Embed $embed;
	private Message $message;

	public function __construct(
		Engine $engine,
		private string $name,
		string $url,
		string $title,
		string $footer,
		int $color
	) {
		$this->__notOtherInstanceConstruct();

		$this->engine  = $engine;
		$this->url     = $url;
		$this->message = new Message();
		$this->embed   = new Embed();
		$this->embed->setTitle($title);
		$this->embed->setFooter($footer);
		$this->embed->setColor($color);
		$this->embed->setThumbnail();
	}

	public function getEngine() : Engine {
		return $this->engine;
	}

	public function getUrl() : string {
		return $this->url;
	}

	public function getEmbed() : Embed {
		return $this->embed;
	}

	public function getMessage() : Message {
		return $this->message;
	}

	public function submit(bool $useEmbed = true) : void {
		$convertType = match ($useEmbed) {
			true  => "embed",
			false => "message"
		};
		$ev = new WebhookSendEvent(
			$this->name,
			$convertType,
			json_encode($this->message),
			$useEmbed ? $this->embed : null
		);
		$ev->call();
		if($ev->isCancelled()) {
			return;
		}
		if(!$useEmbed) {
			$this->message->setContent($ev->getMessage());
		}
		$this->message->addEmbed($ev->getEmbed());
		$this->getEngine()->getServer()->getAsyncPool()->submitTask(new WebhookThread(
			$this->url,
			json_encode($this->message)
		));
	}

}
