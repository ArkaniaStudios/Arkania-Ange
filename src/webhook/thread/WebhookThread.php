<?php
declare(strict_types=1);

namespace arkania\webhook\thread;

use arkania\Engine;
use pocketmine\scheduler\AsyncTask;

class WebhookThread extends AsyncTask {

    private string $url;
    private string $content;

    public function __construct(
        string $url,
        string $content
    ) {
        $this->url = $url;
        $this->content = $content;
    }

    public function onRun() : void {
        $ch = curl_init($this->url);
        if ($ch === false) {
            return;
        }
        curl_setopt_array($ch, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $this->content,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $this->setResult($response);
    }

    public function onCompletion() : void {
        $response = $this->getResult();
        if($response !== '') {
            Engine::getInstance()->getLogger()->error("WebhookError: " . $response);
        }
    }

}