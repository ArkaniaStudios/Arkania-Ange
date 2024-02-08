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

/**
 *     _      ____    _  __     _      _   _   ___      _             __     __  ____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            \ \   / / |___ \
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____   \ \ / /    __) |
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____|   \ V /    / __/
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            \_/    |_____|
 *
 * @author: Julien
 * @link: https://github.com/ArkaniaStudios
 */

namespace arkania\npc\utils;

use arkania\npc\NpcManager;
use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\utils\Limits;
use function preg_match;

class EntityRegistry {
	public const TAG_ID_LIST = "idlist";

	public static function validateIdentifier(string $identifier) : void {
		if (preg_match("/^[a-z0-9_]+:[a-z0-9_]+$/", $identifier) !== 1) {
			throw new InvalidArgumentException("The identifier is invalid. The identifier must be of the form \"namespace:name\". (Only the characters \"a-z0-9_\" are allowed.)");
		}
	}

	public static function validateRuntimeId(int $runtimeId) : void {
		if ($runtimeId < Limits::INT16_MIN || $runtimeId > Limits::INT16_MAX) {
			throw new InvalidArgumentException("The runtime ID is invalid. The runtime ID must be a signed 16-bit integer value."); // for spawnEgg
		}
	}

	/** @var array<string, EntityRegistryEntry> */
	private array $entries = [];
	/** @var array<int, string> */
	private array $runtimeIdToIdentifierMap = [];
	private bool $isDirty                   = false;
	/** @var CacheableNbt<CompoundTag>|null */
	private ?CacheableNbt $identifiers = null;

	/**
	 * @return $this
	 */
	public function add(EntityRegistryEntry $entry) : self {
		$identifier = $entry->getIdentifier();
		$runtimeId  = $entry->getRuntimeId();
		if (isset($this->entries[$identifier])) {
			throw new InvalidArgumentException("The identifier is invalid. The identifier is already in use.");
		}
		if ($runtimeId !== null && ($duplicatedIdentifier = $this->runtimeIdToIdentifierMap[$runtimeId] ?? null) !== null) {
			throw new InvalidArgumentException("The runtimeId is invalid. The runtime ID is duplicated as \"$duplicatedIdentifier\". (\"$runtimeId\")");
		}

		$this->entries[$identifier] = $entry;
		if ($runtimeId !== null) {
			$this->runtimeIdToIdentifierMap[$runtimeId] = $identifier;
		}
		$this->isDirty = true;
		return $this;
	}

	public function get(string $identifier) : ?EntityRegistryEntry {
		return $this->entries[$identifier] ?? null;
	}

	public function getFromRuntimeId(int $runtimeId) : ?EntityRegistryEntry {
		if (($identifier = $this->runtimeIdToIdentifierMap[$runtimeId] ?? null) !== null) {
			return $this->get($identifier);
		}

		return null;
	}

	/**
	 * @return array<string, EntityRegistryEntry>
	 */
	public function getAll() : array {
		return $this->entries;
	}

	/**
	 * @return $this
	 */
	public function remove(string $identifier) : self {
		if (!isset($this->entries[$identifier])) {
			throw new InvalidArgumentException("The specified identifier has not been registered.");
		}
		if ($identifier === NpcManager::getFallbackEntity()) {
			throw new InvalidArgumentException("Cannot be deleted because it is registered as a fallback.");
		}
		if (($runtimeId = $this->entries[$identifier]->getRuntimeId()) !== null) {
			unset($this->runtimeIdToIdentifierMap[$runtimeId]);
		}
		unset($this->entries[$identifier]);
		$this->isDirty = true;
		return $this;
	}

	/**
	 * @return CacheableNbt<CompoundTag>
	 */
	public function getIdentifierTag() : CacheableNbt {
		if ($this->identifiers === null || $this->isDirty) {
			$listTag = new ListTag();
			foreach ($this->entries as $entry) {
				$entryTag = CompoundTag::create();
				$entry->write($entryTag);
				$listTag->push($entryTag);
			}
			$this->identifiers = new CacheableNbt(CompoundTag::create()->setTag(self::TAG_ID_LIST, $listTag));
			$this->isDirty     = false;
		}

		return $this->identifiers;
	}

}
