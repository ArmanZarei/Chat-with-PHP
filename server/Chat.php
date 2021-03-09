<?php

namespace App;

use App\Messages\MessageInterface;
use App\Messages\UserConnectedMessage;
use App\Messages\UserDisconnectMessage;
use App\Messages\UserMessage;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    private $clientsInfo = [];

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->clientsInfo[$conn->resourceId] = [];

        $message = new UserConnectedMessage([]);
        $this->notifyAll($message);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $userMessage = new UserMessage(json_decode($msg, true));
        if (!$userMessage->isValid()) {
            return;
        }

        $this->updateClientInformation($from, ['name' => $userMessage->get('name')]);

        $this->notifyAll($userMessage);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        $message = new UserDisconnectMessage([
            'name' => $this->clientsInfo[$conn->resourceId]['name'],
        ]);
        $this->notifyAll($message);

        unset($this->clientsInfo[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "[ERROR] An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    private function notifyAll(MessageInterface $message) {
        $response = $message->getJsonData();

        foreach ( $this->clients as $client ) {
            $client->send($response);
        }
    }

    private function updateClientInformation($client, $info) {
        foreach ($info as $key => $value) {
            $this->clientsInfo[$client->resourceId][$key] = $value;
        }
    }
}
