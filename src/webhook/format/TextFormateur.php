<?php
declare(strict_types=1);

namespace arkania\utils\format;

function underline(string $text) : string {
    return '__' . $text . '__';
}

function bolt(string $text) : string {
    return '**' . $text . '**';
}

function italic(string $text) : string {
    return '*' . $text . '*';
}

function highlight(string $text) : string {
    return '~~' . $text .  '~~';
}

function title(string $text) : string {
    return '# ' . $text;
}

function subTitle(string $text) : string {
    return '## ' . $text;
}

function littleTitle(string $text) : string {
    return '### ' . $text;
}

function hyperlink(string $text, string $url) : string {
    return '[' . $text . '](' . $url . ')';
}