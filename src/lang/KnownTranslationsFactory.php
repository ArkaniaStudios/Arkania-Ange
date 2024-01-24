<?php

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

final class KnownTranslationsFactory{
	public static function command_language_changed(Translatable|string $param0) : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_LANGUAGE_CHANGED, [
			0 => $param0,
		]);
	}

	public static function command_language_description() : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_LANGUAGE_DESCRIPTION, []);
	}

	public static function command_plugin_description() : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_PLUGIN_DESCRIPTION, []);
	}

	public static function command_plugin_list(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_PLUGIN_LIST, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function command_reply_description() : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_REPLY_DESCRIPTION, []);
	}

	public static function command_reply_no_player() : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_REPLY_NO_PLAYER, []);
	}

	public static function command_tell_description() : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_TELL_DESCRIPTION, []);
	}

	public static function command_tell_message_received(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_TELL_MESSAGE_RECEIVED, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function command_tell_message_sent(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_TELL_MESSAGE_SENT, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function command_version_description() : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_VERSION_DESCRIPTION, []);
	}

	public static function command_version_message(Translatable|string $param0, Translatable|string $param1, Translatable|string $param2, Translatable|string $param3, Translatable|string $param4) : Translatable{
		return new Translatable(KnownTranslationsKeys::COMMAND_VERSION_MESSAGE, [
			0 => $param0,
			1 => $param1,
			2 => $param2,
			3 => $param3,
			4 => $param4,
		]);
	}

	public static function form_cant_open() : Translatable{
		return new Translatable(KnownTranslationsKeys::FORM_CANT_OPEN, []);
	}

	public static function form_cant_use_button() : Translatable{
		return new Translatable(KnownTranslationsKeys::FORM_CANT_USE_BUTTON, []);
	}

	public static function language_name() : Translatable{
		return new Translatable(KnownTranslationsKeys::LANGUAGE_NAME, []);
	}

	public static function player_not_found(Translatable|string $param0) : Translatable{
		return new Translatable(KnownTranslationsKeys::PLAYER_NOT_FOUND, [
			0 => $param0,
		]);
	}

	public static function plugin_invalid_plugin_file(Translatable|string $param0) : Translatable{
		return new Translatable(KnownTranslationsKeys::PLUGIN_INVALID_PLUGIN_FILE, [
			0 => $param0,
		]);
	}

	public static function plugin_load_error() : Translatable{
		return new Translatable(KnownTranslationsKeys::PLUGIN_LOAD_ERROR, []);
	}

	public static function plugin_server_closed() : Translatable{
		return new Translatable(KnownTranslationsKeys::PLUGIN_SERVER_CLOSED, []);
	}

}
