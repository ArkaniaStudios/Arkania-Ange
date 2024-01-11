<?php
declare(strict_types=1);

namespace arkania\webhook;

use arkania\Engine;
use arkania\utils\AlreadyInstantiatedException;
use arkania\utils\BadExtensionException;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use arkania\utils\time\Date;
use arkania\webhook\class\Webhook;
use arkania\webhook\custom\server\ServerEnableWebhook;

use function arkania\utils\format\bolt;

class WebhookManager implements NotOtherInstanceInterface {
    use NotOtherInstanceTrait {
        NotOtherInstanceTrait::__construct as private __notOtherInstanceConstruct;
    }

    /** @var Webhook[] */
    private array $webhooks = [];
    const URL = 'https://discord.com/api/webhooks/1182068778096394290/ueOqbZveD1H6df6vtsVczI2j_6DlYoSwLxr-W-tTRmf5KCKB_iA-4jwgMD43TMaaC2Pu';

    /**
     * @throws BadExtensionException
     * @throws AlreadyRegisteredException
     * @throws AlreadyInstantiatedException
     */
    public function __construct(
        Engine $engine
    ) {
        $this->__notOtherInstanceConstruct();
        $footer = 'Système de logs - Alimenté par ArkaniaStudios' . PHP_EOL . Date::create()->__toString();
        $this->register(
            WebhookNamesKeys::SERVER_START,
            new ServerEnableWebhook(
                $engine,
                self::URL,
                bolt('SEVER - START'),
                $footer,
                0x00FF00
            )
        );
    }

    /**
     * @throws AlreadyRegisteredException
     */
    public function register(string $name, Webhook $webhook) : void {
        if(isset($this->webhooks[$name])){
            throw new AlreadyRegisteredException("The webhook $name is already registered");
        }
        $this->webhooks[$name] = $webhook;
    }

    public function getWebhook(string $name) : ?Webhook {
        return $this->webhooks[$name] ?? null;
    }

}