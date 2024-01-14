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
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\ServerboundPacket;

interface IPacketInterceptor {
	/**
	 * @template TServerboundPacket of ServerboundPacket
	 * @param Closure(TServerboundPacket, NetworkSession) : bool $handler
	 */
	public function interceptIncoming(Closure $handler) : IPacketInterceptor;

	/**
	 * @template TClientboundPacket of ClientboundPacket
	 * @param Closure(TClientboundPacket, NetworkSession) : bool $handler
	 */
	public function interceptOutgoing(Closure $handler) : IPacketInterceptor;

	/**
	 * @template TServerboundPacket of ServerboundPacket
	 * @param Closure(TServerboundPacket, NetworkSession) : bool $handler
	 */
	public function unregisterIncomingInterceptor(Closure $handler) : IPacketInterceptor;

	/**
	 * @template TClientboundPacket of ClientboundPacket
	 * @param Closure(TClientboundPacket, NetworkSession) : bool $handler
	 */
	public function unregisterOutgoingInterceptor(Closure $handler) : IPacketInterceptor;
}
