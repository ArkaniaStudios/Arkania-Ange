<?php
declare(strict_types=1);

namespace arkania\plugins;

interface PluginLoader {

    public function canLoad(string $path) : bool;

    public function loadPlugin(string $file) : void;

    public function getPluginInfo(string $file) : ?PluginInformations;

    public function getAccessProtocol() : string;

}