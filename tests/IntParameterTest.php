<?php

use PHPUnit\Framework\TestCase;
use arkania\commands\parameters\IntParameter;
use pocketmine\command\CommandSender;

class IntParameterTest extends TestCase {
    public function testCanParsePositiveNumber() {
        $intParameter = new IntParameter("test", false);
        $this->assertTrue($intParameter->canParse("123", new \pocketmine\console\ConsoleCommandSender(\pocketmine\Server::getInstance(), \pocketmine\Server::getInstance()->getLanguage())));
    }

    public function testCanParseNegativeNumber() {
        $intParameter = new IntParameter("test", true);
        $this->assertTrue($intParameter->canParse("-123", new \pocketmine\console\ConsoleCommandSender(\pocketmine\Server::getInstance(), \pocketmine\Server::getInstance()->getLanguage())));
    }

    public function testCannotParseNegativeNumberWhenNotAccepted() {
        $intParameter = new IntParameter("test", false);
        $this->assertFalse($intParameter->canParse("-123", new \pocketmine\console\ConsoleCommandSender(\pocketmine\Server::getInstance(), \pocketmine\Server::getInstance()->getLanguage())));
    }

    public function testCannotParseNonNumericString() {
        $intParameter = new IntParameter("test", false);
        $this->assertFalse($intParameter->canParse("abc", new \pocketmine\console\ConsoleCommandSender(\pocketmine\Server::getInstance(), \pocketmine\Server::getInstance()->getLanguage())));
    }

    public function testParsePositiveNumber() {
        $intParameter = new IntParameter("test", false);
        $this->assertEquals(123, $intParameter->parse("123", new \pocketmine\console\ConsoleCommandSender(\pocketmine\Server::getInstance(), \pocketmine\Server::getInstance()->getLanguage())));
    }

    public function testParseNegativeNumber() {
        $intParameter = new IntParameter("test", true);
        $this->assertEquals(-123, $intParameter->parse("-123", new \pocketmine\console\ConsoleCommandSender(\pocketmine\Server::getInstance(), \pocketmine\Server::getInstance()->getLanguage())));
    }
}