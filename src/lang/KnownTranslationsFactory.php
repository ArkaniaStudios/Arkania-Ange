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
