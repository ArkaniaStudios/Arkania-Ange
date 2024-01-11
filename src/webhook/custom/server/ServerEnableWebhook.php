<?php
declare(strict_types=1);

namespace arkania\webhook\custom\server;

use arkania\webhook\class\Webhook;

class ServerEnableWebhook extends Webhook {

    public function send(
        string $serverName,
        string $serverIp,
        int $serverPort,
        int $serverProtocol,
        string $serverVersion,
        string $serverApiVersion,
        int $serverMaxPlayers,
        int $serverOnlinePlayers,
        string $phpVersion
    ) : void {
        $embed = $this->getEmbed();
        $embed->setDescription(
            '- Le serveur **' . $serverName . '** a été démarré avec succès !' . "\n\n" .
            '*Informations:*' . "\n" .
            ' - IP: `' . $serverIp . ':' . $serverPort . '`' . "\n" .
            ' - Version: `' . $serverVersion . '`' . "\n" .
            ' - API: `' . $serverApiVersion . '`' . "\n" .
            ' - Joueurs: `' . $serverOnlinePlayers . '/' . $serverMaxPlayers . '`' . "\n" .
            ' - PHP: `' . $phpVersion . '`' . "\n" .
            ' - Protocol: `' . $serverProtocol . '`' . "\n" .
            ' - Memory: `' . round(memory_get_usage() / 1024 / 1024, 2) . 'MB`'
        );
        $this->submit();
    }

}