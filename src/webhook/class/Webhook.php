<?php
declare(strict_types=1);

namespace arkania\webhook\class;

use arkania\Engine;
use arkania\utils\NotOtherInstanceInterface;
use arkania\utils\NotOtherInstanceTrait;
use arkania\webhook\thread\WebhookThread;

abstract class Webhook implements NotOtherInstanceInterface {
    use NotOtherInstanceTrait {
        NotOtherInstanceTrait::__construct as private __notOtherInstanceConstruct;
    }

    private Engine $engine;
    private string $url;
    private Embed $embed;
    private Message $message;

    public function __construct(
        Engine $engine,
        string $url,
        string $title,
        string $footer,
        int $color
    ) {
        $this->__notOtherInstanceConstruct();

        $this->engine = $engine;
        $this->url = $url;
        $this->message = new Message();
        $this->embed = new Embed();
        $this->embed->setTitle($title);
        $this->embed->setFooter($footer);
        $this->embed->setColor($color);
        $this->embed->setThumbnail();
    }

    public function getEngine() : Engine {
        return $this->engine;
    }

    public function getUrl() : string {
        return $this->url;
    }

    public function getEmbed() : Embed {
        return $this->embed;
    }

    public function getMessage() : Message {
        return $this->message;
    }

    public function submit(bool $useEmbed = true) : void {
        if($useEmbed) {
            $this->message->addEmbed($this->embed);
        }
        $this->getEngine()->getServer()->getAsyncPool()->submitTask(new WebhookThread(
            $this->url,
            json_encode($this->message)
        ));
    }

}