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

use pocketmine\lang\Translatable;

final class KnownTranslationsFactory {
	public static function command_language_changed(string|Translatable $param0) : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_LANGUAGE_CHANGED, [
			0 => $param0,
		]);
	}

	public static function command_language_description() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_LANGUAGE_DESCRIPTION, []);
	}

	public static function command_maintenance_already_off() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_MAINTENANCE_ALREADY_OFF, []);
	}

	public static function command_maintenance_already_on() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_MAINTENANCE_ALREADY_ON, []);
	}

	public static function command_maintenance_description() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_MAINTENANCE_DESCRIPTION, []);
	}

	public static function command_maintenance_disconnect() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_MAINTENANCE_DISCONNECT, []);
	}

	public static function command_maintenance_error() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_MAINTENANCE_ERROR, []);
	}

	public static function command_maintenance_not() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_MAINTENANCE_NOT, []);
	}

	public static function command_maintenance_off() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_MAINTENANCE_OFF, []);
	}

	public static function command_maintenance_on() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_MAINTENANCE_ON, []);
	}

	public static function command_plugin_description() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_PLUGIN_DESCRIPTION, []);
	}

	public static function command_plugin_list(string|Translatable $param0, string|Translatable $param1) : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_PLUGIN_LIST, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function command_reply_description() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_REPLY_DESCRIPTION, []);
	}

	public static function command_reply_no_player() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_REPLY_NO_PLAYER, []);
	}

	public static function command_tell_description() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_TELL_DESCRIPTION, []);
	}

	public static function command_tell_message_received(string|Translatable $param0, string|Translatable $param1) : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_TELL_MESSAGE_RECEIVED, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function command_tell_message_sent(string|Translatable $param0, string|Translatable $param1) : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_TELL_MESSAGE_SENT, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function command_version_description() : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_VERSION_DESCRIPTION, []);
	}

	public static function command_version_message(string|Translatable $param0, string|Translatable $param1, string|Translatable $param2, string|Translatable $param3, string|Translatable $param4) : Translatable {
		return new Translatable(KnownTranslationsKeys::COMMAND_VERSION_MESSAGE, [
			0 => $param0,
			1 => $param1,
			2 => $param2,
			3 => $param3,
			4 => $param4,
		]);
	}

	public static function form_cant_open() : Translatable {
		return new Translatable(KnownTranslationsKeys::FORM_CANT_OPEN, []);
	}

	public static function form_cant_use_button() : Translatable {
		return new Translatable(KnownTranslationsKeys::FORM_CANT_USE_BUTTON, []);
	}

	public static function language_name() : Translatable {
		return new Translatable(KnownTranslationsKeys::LANGUAGE_NAME, []);
	}

	public static function player_not_found(string|Translatable $param0) : Translatable {
		return new Translatable(KnownTranslationsKeys::PLAYER_NOT_FOUND, [
			0 => $param0,
		]);
	}

	public static function plugin_invalid_plugin_file(string|Translatable $param0) : Translatable {
		return new Translatable(KnownTranslationsKeys::PLUGIN_INVALID_PLUGIN_FILE, [
			0 => $param0,
		]);
	}

	public static function plugin_load_error() : Translatable {
		return new Translatable(KnownTranslationsKeys::PLUGIN_LOAD_ERROR, []);
	}

	public static function plugin_server_closed() : Translatable {
		return new Translatable(KnownTranslationsKeys::PLUGIN_SERVER_CLOSED, []);
	}

	public static function server_status_maintenance() : Translatable {
		return new Translatable(KnownTranslationsKeys::SERVER_STATUS_MAINTENANCE, []);
	}

	public static function server_status_offline() : Translatable {
		return new Translatable(KnownTranslationsKeys::SERVER_STATUS_OFFLINE, []);
	}

	public static function server_status_online() : Translatable {
		return new Translatable(KnownTranslationsKeys::SERVER_STATUS_ONLINE, []);
	}

	public static function server_status_restarting() : Translatable {
		return new Translatable(KnownTranslationsKeys::SERVER_STATUS_RESTARTING, []);
	}

	public static function server_status_starting() : Translatable {
		return new Translatable(KnownTranslationsKeys::SERVER_STATUS_STARTING, []);
	}

	public static function server_status_unknown() : Translatable {
		return new Translatable(KnownTranslationsKeys::SERVER_STATUS_UNKNOWN, []);
	}

}
