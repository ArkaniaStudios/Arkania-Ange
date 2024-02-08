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

/*     _      ____    _  __     _      _   _   ___      _              _____   _   _    ____   ___   _   _   _____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            | ____| | \ | |  / ___| |_ _| | \ | | | ____|
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____  |  _|   |  \| | | |  _   | |  |  \| | |  _|
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____| | |___  | |\  | | |_| |  | |  | |\  | | |___
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\         |_____| |_| \_|  \____| |___| |_| \_| |_____|
 *
 * @author Julien
 * @link https://arkaniastudios.com
 * @version 0.0.1-beta
 *
 */

namespace arkania\lang;

final class KnownTranslationsKeys {
	public const COMMAND_LANGUAGE_CHANGED        = "command.language.changed";
	public const COMMAND_LANGUAGE_DESCRIPTION    = "command.language.description";
	public const COMMAND_MAINTENANCE_ALREADY_OFF = "command.maintenance.already.off";
	public const COMMAND_MAINTENANCE_ALREADY_ON  = "command.maintenance.already.on";
	public const COMMAND_MAINTENANCE_DESCRIPTION = "command.maintenance.description";
	public const COMMAND_MAINTENANCE_DISCONNECT  = "command.maintenance.disconnect";
	public const COMMAND_MAINTENANCE_ERROR       = "command.maintenance.error";
	public const COMMAND_MAINTENANCE_NOT         = "command.maintenance.not";
	public const COMMAND_MAINTENANCE_OFF         = "command.maintenance.off";
	public const COMMAND_MAINTENANCE_ON          = "command.maintenance.on";
	public const COMMAND_PLUGIN_DESCRIPTION      = "command.plugin.description";
	public const COMMAND_PLUGIN_LIST             = "command.plugin.list";
	public const COMMAND_REPLY_DESCRIPTION       = "command.reply.description";
	public const COMMAND_REPLY_NO_PLAYER         = "command.reply.no.player";
	public const COMMAND_TELL_DESCRIPTION        = "command.tell.description";
	public const COMMAND_TELL_MESSAGE_RECEIVED   = "command.tell.message.received";
	public const COMMAND_TELL_MESSAGE_SENT       = "command.tell.message.sent";
	public const COMMAND_VERSION_DESCRIPTION     = "command.version.description";
	public const COMMAND_VERSION_MESSAGE         = "command.version.message";
	public const FORM_CANT_OPEN                  = "form.cant.open";
	public const FORM_CANT_USE_BUTTON            = "form.cant.use.button";
	public const LANGUAGE_NAME                   = "language.name";
	public const PLAYER_NOT_FOUND                = "player.not.found";
	public const PLUGIN_INVALID_PLUGIN_FILE      = "plugin.invalid.plugin.file";
	public const PLUGIN_LOAD_ERROR               = "plugin.load.error";
	public const PLUGIN_SERVER_CLOSED            = "plugin.server.closed";
	public const SERVER_STATUS_MAINTENANCE       = "server.status.maintenance";
	public const SERVER_STATUS_OFFLINE           = "server.status.offline";
	public const SERVER_STATUS_ONLINE            = "server.status.online";
	public const SERVER_STATUS_RESTARTING        = "server.status.restarting";
	public const SERVER_STATUS_STARTING          = "server.status.starting";
	public const SERVER_STATUS_UNKNOWN           = "server.status.unknown";
}
