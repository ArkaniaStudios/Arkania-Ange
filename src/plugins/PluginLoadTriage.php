<?php
declare(strict_types=1);

namespace arkania\plugins;

final class PluginLoadTriage {

    /** @var PluginLoadTriageEntry[] */
    public array $plugins = [];

    /** @var string[][] */
    public array $dependencies = [];

}