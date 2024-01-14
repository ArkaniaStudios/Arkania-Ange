<?php
declare(strict_types=1);

namespace arkania\utils;

trait NotOtherInstanceTrait {

    /** @var array<class-string<NotOtherInstanceInterface>, true> */
    private static array $instances = [];

    /**
     * @throws BadExtensionException
     * @throws AlreadyInstantiatedException
     */
    public function __construct() {
        if(!$this instanceof NotOtherinstanceInterface) {
            throw new BadExtensionException('Class ' . static::class . ' must implement ' . NotOtherInstanceInterface::class);
        }
        if(isset(self::$instances[static::class])) {
            throw new AlreadyInstantiatedException('Class ' . static::class . ' already instantiated');
        }
        self::$instances[static::class] = true;
    }
}