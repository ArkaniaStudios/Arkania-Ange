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

namespace arkania\libs\muqsit\simplepackethandler\monitor;

use Closure;
use pocketmine\plugin\Plugin;

final class PacketMonitor implements IPacketMonitor {
	private PacketMonitorListener $listener;

	public function __construct(Plugin $register, bool $handle_cancelled) {
		$this->listener = new PacketMonitorListener($register, $handle_cancelled);
	}

	public function monitorIncoming(Closure $handler) : IPacketMonitor {
		$this->listener->monitorIncoming($handler);
		return $this;
	}

	public function monitorOutgoing(Closure $handler) : IPacketMonitor {
		$this->listener->monitorOutgoing($handler);
		return $this;
	}

	public function unregisterIncomingMonitor(Closure $handler) : IPacketMonitor {
		$this->listener->unregisterIncomingMonitor($handler);
		return $this;
	}

	public function unregisterOutgoingMonitor(Closure $handler) : IPacketMonitor {
		$this->listener->unregisterOutgoingMonitor($handler);
		return $this;
	}
}
