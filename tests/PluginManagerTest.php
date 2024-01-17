<?php

use arkania\plugins\EnginePlugin;
use arkania\plugins\PluginManager;
use arkania\Engine;
use arkania\utils\AlreadyInstantiatedException;
use arkania\utils\BadExtensionException;
use pocketmine\Server;
use PHPUnit\Framework\TestCase;

class PluginManagerTest extends TestCase {
    /**
     * @throws BadExtensionException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws ReflectionException
     * @throws AlreadyInstantiatedException
     */
    public function testLoadPluginsWithValidPath()
    {
        $engine = $this->createMock(Engine::class);
        $server = $this->createMock(Server::class);
        $pluginManager = new PluginManager($engine, $server);

        $path = '/valid/path/to/plugins';
        $loadErrorCount = 0;

        $plugins = $pluginManager->loadPlugins($path, $loadErrorCount);

        $this->assertIsArray($plugins);
        $this->assertEquals(0, $loadErrorCount);
    }

    public function testLoadPluginsWithInvalidPath()
    {
        $engine = $this->createMock(Engine::class);
        $server = $this->createMock(Server::class);
        $pluginManager = new PluginManager($engine, $server);

        $path = '/invalid/path/to/plugins';
        $loadErrorCount = 0;

        $plugins = $pluginManager->loadPlugins($path, $loadErrorCount);

        $this->assertIsArray($plugins);
        $this->assertGreaterThan(0, $loadErrorCount);
    }

    /**
     * @throws BadExtensionException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws AlreadyInstantiatedException
     */
    public function testEnablePluginWhenPluginIsDisabled()
    {
        $engine = $this->createMock(Engine::class);
        $server = $this->createMock(Server::class);
        $plugin = $this->createMock(EnginePlugin::class);
        $pluginManager = new PluginManager($engine, $server);

        $plugin->method('isEnabled')->willReturn(false);

        $result = $pluginManager->enablePlugin($plugin);

        $this->assertTrue($result);
    }

    /**
     * @throws BadExtensionException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws AlreadyInstantiatedException
     */
    public function testEnablePluginWhenPluginIsAlreadyEnabled()
    {
        $engine = $this->createMock(Engine::class);
        $server = $this->createMock(Server::class);
        $plugin = $this->createMock(EnginePlugin::class);
        $pluginManager = new PluginManager($engine, $server);

        $plugin->method('isEnabled')->willReturn(true);

        $result = $pluginManager->enablePlugin($plugin);

        $this->assertTrue($result);
    }

    /**
     * @throws BadExtensionException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws AlreadyInstantiatedException
     */
    public function testDisablePluginWhenPluginIsEnabled()
    {
        $engine = $this->createMock(Engine::class);
        $server = $this->createMock(Server::class);
        $plugin = $this->createMock(EnginePlugin::class);
        $pluginManager = new PluginManager($engine, $server);

        $plugin->method('isEnabled')->willReturn(true);

        $pluginManager->disablePlugin($plugin);

        $this->assertFalse($plugin->isEnabled());
    }

    public function testDisablePluginWhenPluginIsAlreadyDisabled()
    {
        $engine = $this->createMock(Engine::class);
        $server = $this->createMock(Server::class);
        $plugin = $this->createMock(EnginePlugin::class);
        $pluginManager = new PluginManager($engine, $server);

        $plugin->method('isEnabled')->willReturn(false);

        $pluginManager->disablePlugin($plugin);

        $this->assertFalse($plugin->isEnabled());
    }
}