<?php
declare(strict_types=1);

namespace arkania\webhook\class;

final class Embed {

    const ARKANIA_LOGO = 'https://cdn.discordapp.com/attachments/1058448782481690714/1129935696497475737/logo_rond-modified.png';
    private array $data;

    public function __toArray() : array {
        return $this->data;
    }

    public function setTitle(string $title) : self {
        $this->data['title'] = $title;
        return $this;
    }

    public function setDescription(string $description) : self {
        $this->data['description'] = $description;
        return $this;
    }

    public function setFooter(string $footer, ?string $iconUrl = self::ARKANIA_LOGO) : self {
        $this->data['footer'] = [
            'text' => $footer,
            'icon_url' => $iconUrl
        ];
        return $this;
    }

    public function setColor(int $color) : self {
        $this->data['color'] = $color;
        return $this;
    }

    public function setThumbnail(string $url = self::ARKANIA_LOGO) : self {
        $this->data['thumbnail'] = [
            'url' => $url
        ];
        return $this;
    }

    public function setImage(string $url) : self {
        $this->data['image'] = [
            'url' => $url
        ];
        return $this;
    }

    public function setAuthor(string $name, ?string $url = null, ?string $iconUrl = self::ARKANIA_LOGO) : self {
        $this->data['author'] = [
            'name' => $name,
            'url' => $url,
            'icon_url' => $iconUrl
        ];
        return $this;
    }

    public function addField(string $name, string $value, bool $inline = false) : self {
        $this->data['fields'][] = [
            'name' => $name,
            'value' => $value,
            'inline' => $inline
        ];
        return $this;
    }
}