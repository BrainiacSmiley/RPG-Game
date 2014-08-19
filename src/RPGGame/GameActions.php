<?php
    namespace RPGGame;

    class GameActions {
        protected $senderData;

        public function __construct() {
            $this->senderData = array();
        }

        public function getMap($sender, $input) {
            $userData = $this->_getSenderData($sender);
            if ($userData instanceof UserData) {
                $map = $userData->getTiledMap()->getJSMap();

                return array(
                    'action' => 'setMap',
                    'data'   => $map,
                );
            } else {
                return $this->_dataError('UserData Corrupted');
            }
        }

        public function changeTile($sender, $input) {
            $userData = $this->_getSenderData($sender);
            if ($userData instanceof UserData) {
                if ($userData->getSelectedTile() > 0) {
                    if ($userData->getTiledMap()->changeTile($input->tile, $userData->getSelectedTile())) {
                        return array(
                            'action' => 'setMap',
                            'data'   => $userData->getTiledMap()->getJSMap(),
                        );
                    }
                    else {
                        return $this->_dataError('Tile cant be placed here!');
                    }

                } else {
                    return $this->_dataError('Please select a tile!');
                }

            }

        }

        /**
         * To set the mapSize.
         *
         * @param mixed $sender
         * @param array $input The input data.
         *
         * @return array
         */
        public function setViewSize($sender, $input) {
            $userData = $this->_getSenderData($sender);

            $userData->setViewSize($input);

            return $this->getMap($sender, $input);
        }

        public function tileSelected($sender, $input) {
            $userData = $this->_getSenderData($sender);
            if ($userData instanceof UserData) {
                $userData->setSelectedTile($input->tileId);

                return array(
                    'action' => 'success',
                    'data'   => 'Tile ' . $input->tileId . ' selected'
                );
            } else {
                return $this->_dataError('UserData Corrupted');
            }
        }

        public function deleteSenderData($sender) {
            unset($this->senderData[$sender]);
        }

        /**
         * Gets the sender Data.
         *
         * @param mixed $sender The sender.
         *
         * @return UserData
         */
        private function _getSenderData($sender) {
            if (!isset($this->senderData[$sender])) {
                $this->senderData[$sender] = new UserData();
            }

            return $this->senderData[$sender];
        }

        /**
         * Function to handle errors.
         *
         * @param string $msg The error message.
         *
         * @return array
         */
        private function _dataError($msg) {
            return array(
                'action' => 'error',
                'data'   => $msg,
            );
        }
    }
?>