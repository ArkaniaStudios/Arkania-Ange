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

namespace arkania\events;

use arkania\Engine;
use pocketmine\event\Event;
use pocketmine\event\EventPriority;
use pocketmine\event\HandlerListManager;
use pocketmine\event\Listener;
use pocketmine\event\RegisteredListener;
use pocketmine\timings\Timings;
use pocketmine\utils\Utils;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use function count;
use function get_class;

class ListenerManager {
	private function getEventsHandledBy(ReflectionMethod $method) : ?string {
		try {
			if ($method->isStatic() || !$method->getDeclaringClass()->implementsInterface(Listener::class)) {
				return null;
			}
			$parameters = $method->getParameters();
			if (count($parameters) !== 1) {
				return null;
			}
			$paramType = $parameters[0]->getType();
			if (!$paramType instanceof ReflectionNamedType || $paramType->isBuiltin()) {
				return null;
			}

			$paramClass = $paramType->getName();
			$eventClass = new ReflectionClass($paramClass);
			if (!$eventClass->isSubclassOf(Event::class)) {
				return null;
			}
			return $eventClass->getName();
		} catch (ReflectionException $e) {
			Engine::getInstance()->getLogger()->warning("ReflectionException: " . $e->getMessage());
		}
		return null;
	}

	/**
	 * @throws ReflectionException
	 */
	public function registerListener(EngineListener|Listener $event) : ?RegisteredListener {
		$reflection = new ReflectionClass(get_class($event));
		foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			$handlerClosure   = $method->getClosure($event);
			$engine           = Engine::getInstance();
			$registerListener = new RegisteredListener(
				$handlerClosure,
				EventPriority::NORMAL,
				$engine,
				false,
				Timings::getEventHandlerTimings(
					Event::class,
					Utils::getNiceClosureName($handlerClosure),
					$engine->getDescription()->getFullName()
				)
			);
			HandlerListManager::global()->getListFor($this->getEventsHandledBy($method))->register($registerListener);
		}
		return $registerListener ?? null;
	}

	public function registerListeners(EngineListener|Listener ...$listeners) : void {
		foreach ($listeners as $listener) {
			$this->registerListener($listener);
		}
	}

}
