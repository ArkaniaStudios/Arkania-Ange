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

namespace arkania\utils\format;

function underline(string $text) : string {
	return '__' . $text . '__';
}

function bolt(string $text) : string {
	return '**' . $text . '**';
}

function italic(string $text) : string {
	return '*' . $text . '*';
}

function highlight(string $text) : string {
	return '~~' . $text . '~~';
}

function title(string $text) : string {
	return '# ' . $text;
}

function subTitle(string $text) : string {
	return '## ' . $text;
}

function littleTitle(string $text) : string {
	return '### ' . $text;
}

function hyperlink(string $text, string $url) : string {
	return '[' . $text . '](' . $url . ')';
}
