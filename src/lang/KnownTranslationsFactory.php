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
	public static function language_name() : Translatable{
		return new Translatable(KnownTranslationsKeys::LANGUAGE_NAME, []);
	}

	public static function plugin_invalid_plugin_file(Translatable|string $param0) : Translatable{
		return new Translatable(KnownTranslationsKeys::PLUGIN_INVALID_PLUGIN_FILE, [
			0 => $param0,
		]);
	}

	public static function plugin_load_error() : Translatable{
		return new Translatable(KnownTranslationsKeys::PLUGIN_LOAD_ERROR, []);
	}

}
