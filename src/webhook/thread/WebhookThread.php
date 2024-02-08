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

namespace arkania\webhook\thread;

use arkania\Engine;
use pocketmine\scheduler\AsyncTask;
use function curl_close;
use function curl_exec;
use function curl_init;
use function curl_setopt_array;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POST;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_SSL_VERIFYHOST;
use const CURLOPT_SSL_VERIFYPEER;

class WebhookThread extends AsyncTask {
	private string $url;
	private string $content;

	public function __construct(
		string $url,
		string $content
	) {
		$this->url     = $url;
		$this->content = $content;
	}

	public function onRun() : void {
		$ch = curl_init($this->url);
		if ($ch === false) {
			return;
		}
		curl_setopt_array($ch, [
			CURLOPT_POST       => true,
			CURLOPT_POSTFIELDS => $this->content,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json"
			],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		]);
		$response = curl_exec($ch);
		curl_close($ch);
		$this->setResult($response);
	}

	public function onCompletion() : void {
		$response = $this->getResult();
		if($response !== '') {
			Engine::getInstance()->getLogger()->error("WebhookError: " . $response);
		}
	}

}
