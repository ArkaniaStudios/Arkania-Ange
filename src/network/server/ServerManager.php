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

namespace arkania\network\server;

use arkania\database\result\SqlSelectResult;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use arkania\webhook\AlreadyRegisteredException;
use function count;

class ServerManager implements NotOtherInstanceInterface {
	use NotOtherInstanceTrait {
		NotOtherInstanceTrait::__construct as traitConstruct;
	}

	/** @var ServerInterface[] */
	private array $servers = [];

	public function __construct() {
		$this->traitConstruct();
	}

	/**
	 * @throws AlreadyRegisteredException
	 */
	public function addServer(ServerInterface $server) : void {
		if(isset($this->servers[$server->getId()])) {
			throw new AlreadyRegisteredException('Server with id ' . $server->getId() . ' is already registered');
		}
		ServersIds::addServer($server->getPort(), $server->getId(), $server->getName());
		$server->getEngine()->getDataBaseManager()->getConnector()->executeSelect(
			'SELECT * FROM servers WHERE id = ?',
			[$server->getId()]
		)->then(function (SqlSelectResult $result) use ($server) : void {
			if(count($result->getRows()) <= 0) {
				$server->getEngine()->getDataBaseManager()->getConnector()->executeInsert(
					'INSERT INTO servers (id, name, ip, port, status) VALUES (?, ?, ?, ?, ?)',
					[$server->getId(), $server->getName(), $server->getIp(), $server->getPort(), ServerInterface::STATUS_ONLINE]
				);
			} else {
				$server->getStatus()->then(function (SqlSelectResult $result) use ($server) : void {
					if(count($result->getRows()) <= 0) {
						return;
					}
					if($result->getRows()[0]['status'] === ServerInterface::STATUS_WHITELIST) {
						$server->getEngine()->getDataBaseManager()->getConnector()->executeChange(
							'UPDATE servers SET name = ?, ip = ?, port = ? WHERE id = ?',
							[$server->getName(), $server->getIp(), $server->getPort(), $server->getId()]
						);
						$server->setStatus(ServerInterface::STATUS_WHITELIST);
						$this->servers[$server->getId()] = $server;
						return;
					}
					$server->getEngine()->getDataBaseManager()->getConnector()->executeChange(
						'UPDATE servers SET name = ?, ip = ?, port = ?, status = ? WHERE id = ?',
						[$server->getName(), $server->getIp(), $server->getPort(), $server->getStringStatus(), $server->getId()]
					);
				});
			}
			$this->servers[$server->getId()] = $server;
		});
	}

	public function getServer(int $id) : ?ServerInterface {
		return $this->servers[$id] ?? null;
	}

	public function getServers() : array {
		return $this->servers;
	}

	public function removeServer(int $id) : void {
		unset($this->servers[$id]);
	}

	public function removeAllServers() : void {
		$this->servers = [];
	}

}
