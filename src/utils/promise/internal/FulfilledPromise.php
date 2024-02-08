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

namespace arkania\utils\promise\internal;

use arkania\utils\promise\PromiseInterface;
use InvalidArgumentException;

use Throwable;
use function arkania\utils\promise\resolve;

final class FulfilledPromise implements PromiseInterface {
	/** @var mixed|null */
	private mixed $value;

	public function __construct($value = null) {
		if($value instanceof PromiseInterface) {
			throw new InvalidArgumentException(
				"You cannot create arkania\\utils\\promise\\internal\\FulfilledPromise with a promise. Use arkania\\utils\\promise\\resolve(\$promiseOrValue) instead."
			);
		}
		$this->value = $value;
	}

	public function then(callable $onFulfilled = null, callable $onRejected = null) : PromiseInterface {
		if (null === $onFulfilled) {
			return $this;
		}

		try {
			return resolve($onFulfilled($this->value));
		} catch (Throwable $exception) {
			return new RejectedPromise($exception);
		}
	}

	public function catch(callable $onRejected) : PromiseInterface {
		return $this;
	}

	/**
	 * @phpstan-param callable(): mixed $onFulfilledOrRejected
	 */
	public function finally(callable $onFulfilledOrRejected) : PromiseInterface {
		return $this->then(function ($value) use ($onFulfilledOrRejected) : PromiseInterface {
			return resolve($onFulfilledOrRejected())->then(function () use ($value) {
				return $value;
			});
		});
	}

	public function cancel() : void {
	}

	/**
	 * @deprecated 3.0.0 Use `catch()` instead
	 * @see self::catch()
	 */
	public function otherwise(callable $onRejected) : PromiseInterface {
		return $this->catch($onRejected);
	}

	/**
	 * @deprecated 3.0.0 Use `finally()` instead
	 * @see self::finally()
	 */
	public function always(callable $onFulfilledOrRejected) : PromiseInterface {
		return $this->finally($onFulfilledOrRejected);
	}

	public function wait() : void {
		// NOOP
	}

	public function isResolved() : bool {
		return true;
	}

}
