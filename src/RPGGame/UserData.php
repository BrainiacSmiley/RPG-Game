<?php
    namespace RPGGame;


    class UserData {
        private $_selectedTile;
        private $_map;
        private $_viewSize;

        /**
         * Handles all user related data.
         *
         * In later versions this should be stored and handled in database too.
         */
        public function __construct() {
            $this->_selectedTile = 0;
            $this->_map          = new TiledMap();
            $this->_viewSize     = (object)array(
                'width'  => 0,
                'height' => 0,
            );
        }

        /**
         * Sets the view port given from the canvas to handle the appropriate map size.
         *
         * @param array $viewSize An array with the width and height.
         */
        public function setViewSize($viewSize) {
            $this->_viewSize = $viewSize;
            $this->_map->setViewPort($viewSize->width, $viewSize->height);
        }

        /**
         * Sets the selected tileId for editor mode.
         *
         * @param integer $selectedTile The tileId.
         */
        public function setSelectedTile($selectedTile) {
            $this->_selectedTile = $selectedTile;
        }

        /**
         * Returns the selected tileId in editor mode.
         *
         * @return integer
         */
        public function getSelectedTile() {
            return $this->_selectedTile;
        }

        /**
         * Returns the whole game map.
         *
         * @return TiledMap
         */
        public function getTiledMap() {
            return $this->_map;
        }

        /**
         * To update a tile in editor mode.
         *
         * @param object $tile All values for setting a new tile.
         */
        public function updateMap($tile) {
            $this->_map[$tile->x][$tile->y] = new Tile($this->_selectedTile);
        }
    }

    ?>