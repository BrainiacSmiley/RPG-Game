<?php
    namespace RPGGame;

    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class Dispatcher implements MessageComponentInterface {
        protected $clients;
        protected $gameActions;

        public function __construct() {
            $this->clients     = new \SplObjectStorage;
            $this->gameActions = new GameActions();
        }

        public function onOpen(ConnectionInterface $conn) {
            // Store the new connection to send messages to later
            $this->clients->attach($conn);

            echo "New connection! ({$conn->resourceId})\n";
        }

        public function onMessage(ConnectionInterface $sender, $msg) {
            $input       = json_decode($msg);
            $inputAction = $input->action;
            $result      = $this->gameActions->$inputAction($sender->resourceId, $input->data);

            $json = json_encode($result);
            $sender->send($json);
        }

        public function onClose(ConnectionInterface $conn) {
            // The connection is closed, remove it, as we can no longer send it messages
            $this->gameActions->deleteSenderData($conn->resourceId);
            $this->clients->detach($conn);

            echo "Connection {$conn->resourceId} has disconnected\n";
        }

        public function onError(ConnectionInterface $conn, \Exception $e) {
            echo "An error has occurred: {$e->getMessage()}\n";

            $conn->close();
        }
    }
?>