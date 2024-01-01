<?php
declare(strict_types=1);

namespace arkania\plugins;

final class PluginLoadTriageEntry {

    public function __construct(
        private readonly string             $file,
        public readonly PluginLoader        $loader,
        public readonly PluginInformations $informations
    ) {}

    public function getFile() : string {
        return $this->file;
    }

    public function getLoader() : PluginLoader {
        return $this->loader;
    }

    public function getInformations() : PluginInformations {
        return $this->informations;
    }

}