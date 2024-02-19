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

namespace arkania\commands;

use arkania\commands\parameters\Parameter;
use arkania\commands\parameters\TextParameter;
use arkania\lang\KnownTranslationsFactory;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\lang\Translatable;
use function array_slice;
use function count;
use function implode;
use function is_array;
use function trim;
use function usort;
use const PHP_INT_MAX;

abstract class CommandBase extends Command {
	/** @var Parameter[][] */
	private array $parameters = [];
	/** @var CommandBase[] */
	private array $subCommands = [];

	public function __construct(
		string $name,
		string|Translatable $description = "",
		null|string|Translatable $usageMessage = null,
		array $subCommands = [],
		array $aliases = []
	) {
		parent::__construct($name, $description, $usageMessage, $aliases);
		foreach ($this->getCommandParameter() as $position => $parameter) {
			$this->addParameter($position, $parameter);
		}
		foreach ($subCommands as $subCommand) {
			$this->subCommands[$subCommand->getName()] = $subCommand;
		}
	}

	abstract public function onRun(CommandSender $sender, array $parameters) : void;
	abstract public function getCommandParameter() : array;

    final public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        $passArgs = [];
        if (count($args) > 0) {
            if (isset($this->subCommands[($label = $args[0])])) {
                $cmd = $this->subCommands[$label];
                if (!$cmd->testPermissionSilent($sender)) {
                    $sender->sendMessage(KnownTranslationFactory::commands_generic_permission());
                    return;
                }
                $cmd->execute($sender, $commandLabel, array_slice($args, 1));
                return;
            }
            $passArgs = $this->parseArguments($args, $sender);
        } elseif (!empty($this->parameters)) {
            foreach ($this->parameters as $parameter) {
                if (!$parameter[0]->isOptional()) {
                    $sender->sendMessage(KnownTranslationFactory::commands_generic_usage($this->usageMessage ?? '/' . $this->getName()));
                    return;
                }
            }
        }
        if ($passArgs !== null) {
            try {
                $this->onRun($sender, $passArgs);
            } catch (InvalidCommandSyntaxException $e) {
                if (!$e->getParameter()->isOptional()) {
                    $sender->sendMessage(KnownTranslationFactory::commands_generic_usage($this->usageMessage ?? '/' . $this->getName()));
                }
            }
        }
    }

	public function addParameter(int $position, Parameter $parameter) : void {
		if($position < 0) {
			throw new InvalidArgumentException("Position must be positive");
		}
		if(isset($this->parameters[$position])) {
			throw new InvalidArgumentException("Cannot add parameter at position $position, parameter already exists");
		}
		foreach ($this->parameters[$position] ?? [] as $param) {
			if($param instanceof TextParameter) {
				throw new InvalidArgumentException("Cannot add parameter at position $position, text parameter already exists");
			}
			if($param->isOpional() && !$parameter->isOptional()) {
				throw new InvalidArgumentException("Cannot add required parameter at position $position, optional parameter already exists");
			}
		}
		$this->parameters[$position][] = $parameter;
	}

    private function parseArguments(array $rawArgs, CommandSender $sender) : ?array {
        $return = [];
        if(!!empty($this->parameters) && count($rawArgs) > 0) {
            return $return;
        }
        $offset = 0;
        if(count($rawArgs) > 0) {
            foreach ($this->parameters as $position => $parameter) {
                usort($parameter, function (Parameter $a, Parameter $b) : int {
                    if ($a->getSpanLength() === PHP_INT_MAX) {
                        return 1;
                    }

                    return -1;
                });
                $parsed   = false;
                $optional = false;
                foreach ($parameter as $param) {
                    $p = trim(implode(" ", array_slice($rawArgs, $offset, ($len = $param->getSpanLength()))));
                    if ($param->isOptional()) {
                        $optional = true;
                    }
                    if ($p !== "" && $param->canParse($p, $sender)) {
                        $k      = $param->getName();
                        $result = (clone $param)->parse($p, $sender);
                        if (isset($return[$k]) && !is_array($return[$k])) {
                            $old = $return[$k];
                            unset($return[$k]);
                            $return[$k]   = [$old];
                            $return[$k][] = $result;
                        } else {
                            $return[$k] = $result;
                        }
                        $offset += $len;
                        $parsed = true;
                        break;
                    } else if ($param->isOptional()) {
                        $parsed = true;
                        break;
                    } else {
                        $sender->sendMessage(KnownTranslationsFactory::command_argument_invalid($param->getName()));
                        return null;
                    }
                }
                if (!$parsed && !$optional) {
                    return $return;
                }
            }
        }
        return $return;
    }

	public function getParameters() : array {
		return $this->parameters;
	}

	public function getSubCommands() : array {
		return $this->subCommands;
	}

}
