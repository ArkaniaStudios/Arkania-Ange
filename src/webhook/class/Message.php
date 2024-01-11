<?php
declare(strict_types=1);

namespace arkania\webhook\class;

use JsonSerializable;
use pmmp\thread\ThreadSafeArray;

final class Message implements JsonSerializable {

    private array $data;


    public function setContent(string $content) : void {
        $this->data['content'] = $content;
    }

    public function setName(string $name) : void {
        $this->data['username'] = $name;
    }

    public function setAvatar(string $url) : void {
        $this->data['avatar_url'] = $url;
    }

    public function setTts(bool $tts) : void {
        $this->data['tts'] = $tts;
    }

    public function addEmbed(Embed $embed) : void {
        $this->data['embeds'][] = $embed->__toArray();
    }

    public function jsonSerialize() : array {
        return $this->data;
    }

}