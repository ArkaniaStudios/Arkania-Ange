<?php
declare(strict_types=1);

namespace arkania\database\mysqli;

/**
 * @see https://dev.mysql.com/doc/internals/en/com-query-response.html#column-type
 */
interface MysqlTypes {

    public const DECIMAL = 0x00;
    public const TINY = 0x01;
    public const SHORT = 0x02;
    public const LONG = 0x03;
    public const FLOAT = 0x04;
    public const DOUBLE = 0x05;
    public const NULL = 0x06;
    public const TIMESTAMP = 0x07;
    public const LONGLONG = 0x08;
    public const INT24 = 0x09;
    public const DATE = 0x0a;
    public const TIME = 0x0b;
    public const DATETIME = 0x0c;
    public const YEAR = 0x0d;
    public const NEWDATE = 0x0e;
    public const VARCHAR = 0x0f;
    public const BIT = 0x10;
    public const TIMESTAMP2 = 0x11;
    public const DATETIME2 = 0x12;
    public const TIME2 = 0x13;
    public const NEWDECIMAL = 0xf6;
    public const ENUM = 0xf7;
    public const SET = 0xf8;
    public const TINY_BLOB = 0xf9;
    public const MEDIUM_BLOB = 0xfa;
    public const LONG_BLOB = 0xfb;
    public const BLOB = 0xfc;
    public const VAR_STRING = 0xfd;
    public const STRING = 0xfe;
    public const GEOMETRY = 0xff;

}