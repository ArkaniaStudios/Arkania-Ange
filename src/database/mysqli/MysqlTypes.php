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

namespace arkania\database\mysqli;

/**
 * @see https://dev.mysql.com/doc/internals/en/com-query-response.html#column-type
 */
interface MysqlTypes {
	public const DECIMAL     = 0x00;
	public const TINY        = 0x01;
	public const SHORT       = 0x02;
	public const LONG        = 0x03;
	public const FLOAT       = 0x04;
	public const DOUBLE      = 0x05;
	public const NULL        = 0x06;
	public const TIMESTAMP   = 0x07;
	public const LONGLONG    = 0x08;
	public const INT24       = 0x09;
	public const DATE        = 0x0A;
	public const TIME        = 0x0B;
	public const DATETIME    = 0x0C;
	public const YEAR        = 0x0D;
	public const NEWDATE     = 0x0E;
	public const VARCHAR     = 0x0F;
	public const BIT         = 0x10;
	public const TIMESTAMP2  = 0x11;
	public const DATETIME2   = 0x12;
	public const TIME2       = 0x13;
	public const NEWDECIMAL  = 0xF6;
	public const ENUM        = 0xF7;
	public const SET         = 0xF8;
	public const TINY_BLOB   = 0xF9;
	public const MEDIUM_BLOB = 0xFA;
	public const LONG_BLOB   = 0xFB;
	public const BLOB        = 0xFC;
	public const VAR_STRING  = 0xFD;
	public const STRING      = 0xFE;
	public const GEOMETRY    = 0xFF;

}
