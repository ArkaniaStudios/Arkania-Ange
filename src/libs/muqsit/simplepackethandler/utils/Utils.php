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

namespace arkania\libs\muqsit\simplepackethandler\utils;

use arkania\npc\base\CustomEntity;
use arkania\npc\base\SimpleEntity;
use arkania\utils\Loader;
use Closure;
use InvalidArgumentException;
use pocketmine\entity\Location;
use ReflectionFunction;
use ReflectionNamedType;
use function count;
use function implode;
use function is_a;
use function strtolower;

final class Utils {
	/**
	 * @param string[] $params
	 * @return string[]
	 */
	public static function parseClosureSignature(Closure $closure, array $params, string $return_type) : array {
		/** @noinspection PhpUnhandledExceptionInspection */
		$method = new ReflectionFunction($closure);
		$type   = $method->getReturnType();
		if(!($type instanceof ReflectionNamedType) || $type->allowsNull() || $type->getName() !== $return_type) {
			throw new InvalidArgumentException("Return value of {$method->getName()} must be {$return_type}");
		}

		$parsed_params = [];
		$parameters    = $method->getParameters();
		if(count($parameters) === count($params)) {
			$parameter_index = 0;
			foreach($parameters as $parameter) {
				$parameter_type    = $parameter->getType();
				$parameter_compare = $params[$parameter_index++];
				if($parameter_type instanceof ReflectionNamedType && !$parameter_type->allowsNull() && is_a($parameter_type->getName(), $parameter_compare, true)) {
					$parsed_params[] = $parameter_type->getName();
					continue;
				}
				break;
			}

			if(count($parsed_params) === count($params)) {
				return $parsed_params;
			}
		}

		throw new InvalidArgumentException("Closure must satisfy signature (" . implode(", ", $params) . ") : {$return_type}");
	}

	public static function getEntityById(Location $location, int|string $id) : null|CustomEntity|SimpleEntity {
		if(!isset(Loader::$entities[strtolower($id)])) {
			return null;
		}
		return new Loader::$entities[strtolower($id)]($location);

	}

}
