<?php
declare(strict_types=1);

namespace arkania\events\webhook;

use arkania\webhook\class\Embed;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

class WebhookSendEvent extends Event implements Cancellable {
    use CancellableTrait;

    private string $webhookName;
    private string $type;
    private string $message;
    private ?Embed $embed;


    public function __construct(
        string $webhookName,
        string $type,
        string $message,
        Embed $embed = null
    ) {
        $this->webhookName = $webhookName;
        $this->type = $type;
        $this->message = $message;
        $this->embed = $embed;
    }

    public function getWebhookName(): string {
        return $this->webhookName;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function getEmbed(): ?Embed {
        return $this->embed;
    }

    public function setEmbed(Embed $embed): void {
        $this->embed = $embed;
    }

}