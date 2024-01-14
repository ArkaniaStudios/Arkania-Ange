<?php

/*
 *     _      ____    _  __     _      _   _   ___      _              _____   _   _    ____   ___   _   _   _____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            | ____| | \ | |  / ___| |_ _| | \ | | | ____|
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____  |  _|   |  \| | | |  _   | |  |  \| | |  _|
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____| | |___  | |\  | | |_| |  | |  | |\  | | |___
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\         |_____| |_| \_|  \____| |___| |_| \_| |_____|
 *
 * @author Julien
 * @link https://arkaniastudios.com
 * @version 0.0.1
 *
 */

declare(strict_types=1);

namespace arkania\libs\muqsit\simplepackethandler\interceptor;

use Closure;
use pocketmine\plugin\Plugin;

final class PacketInterceptor implements IPacketInterceptor {
	private PacketInterceptorListener $listener;

	public function __construct(Plugin $register, int $priority, bool $handle_cancelled) {
		$this->listener = new PacketInterceptorListener($register, $priority, $handle_cancelled);
	}

	public function interceptIncoming(Closure $handler) : IPacketInterceptor {
		$this->listener->interceptIncoming($handler);
		return $this;
	}

	public function interceptOutgoing(Closure $handler) : IPacketInterceptor {
		$this->listener->interceptOutgoing($handler);
		return $this;
	}

	public function unregisterIncomingInterceptor(Closure $handler) : IPacketInterceptor {
		$this->listener->unregisterIncomingInterceptor($handler);
		return $this;
	}

	public function unregisterOutgoingInterceptor(Closure $handler) : IPacketInterceptor {
		$this->listener->unregisterOutgoingInterceptor($handler);
		return $this;
	}
}
