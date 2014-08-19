<?php

    namespace RPGGame;


    class TiledMap {
        const MAP_WIDTH  = 200;
        const MAP_HEIGHT = 200;

        private $_map;
        private $_offsetX;
        private $_offsetY;
        private $_jsMapWidth;
        private $_jsMapHeight;

        /**
         * Creates the whole Map.
         */
        public function __construct() {
            $possibleIds    = array(1, 2, 3);
            $this->_map     = array();
            $this->_offsetX = 0;
            $this->_offsetY = 0;

            for ($x = 0; $x < self::MAP_WIDTH; $x++) {
                for ($y = 0; $y < self::MAP_HEIGHT; $y++) {
                    //$tile               = new Tile($possibleIds[array_rand($possibleIds)]);
                    $tile               = new Tile(Tile::MATERIAL_ROCK, null, 5, $x, $y);
                    $this->_map[$x][$y] = $tile;
                }
            }
        }

        /**
         * Creates the reduced map for the viewport.
         *
         * @return array
         */
        public function getJSMap() {
            $jsMap  = [[]];
            $jsMapX = 0;
            $jsMapY = 0;

            /*$minX = (int)-$this->_jsMapWidth + $this->_offsetX > 0 ? (int)-$this->_jsMapWidth : 0;
            $maxX = $this->_jsMapWidth * 2 + $this->_offsetX < self::MAP_WIDTH ? $this->_jsMapWidth * 2 : self::MAP_WIDTH-1;
            $minY = (int)-$this->_jsMapHeight + $this->_offsetY > 0 ? (int)-$this->_jsMapHeight : 0;
            $maxY = $this->_jsMapHeight * 2 + $this->_offsetY < self::MAP_HEIGHT ? $this->_jsMapHeight * 2 : self::MAP_HEIGHT-1;*/
            $minX = 0;
            $maxX = $this->_jsMapWidth * 3;
            $minY = 0;
            $maxY = $this->_jsMapHeight * 3;

            for ($x = $minX; $x < $maxX; $x++) {
                for ($y = $minY; $y < $maxY; $y++) {
                    $tile                    = $this->_getTile($x, $y);
                    $jsMap[$jsMapX][$jsMapY] = (int)$tile->getImageId();
                    $jsMapY++;
                }
                $jsMapX++;
                $jsMapY = 0;
            }

            return $jsMap;
        }

        /**
         * To change the viewportsize for fullscreen or different screensizes.
         *
         * @param integer $jsMapWidth  Viewport width.
         * @param integer $jsMapHeight Viewport height.
         */
        public function setViewPort($jsMapWidth, $jsMapHeight) {
            $this->_jsMapWidth  = $jsMapWidth;
            $this->_jsMapHeight = $jsMapHeight;
        }

        /**
         * Changes a tile and returns true is change was successful, false otherwise.
         *
         * @param object  $selectedTile The tile object send form the client.
         * @param integer $newMaterial  The new materialId.
         *
         * @return boolean
         */
        public function changeTile($selectedTile, $newMaterial) {
            $newTile      = new Tile($newMaterial);
            $tileToChange = $this->_getTile($selectedTile->x, $selectedTile->y);

            //check te tileToReplace itself
            if (!$tileToChange->canBeReplacedBy($newMaterial)) {
                return false;
            }

            //check the surrounding
            $allSurroundingTiles = $this->_getSurroundingTiles($selectedTile);
            foreach ($allSurroundingTiles as $surroundingTile) {
                if (!$surroundingTile->tile->canBeReplacedBy($newTile->getMaterials())) {
                    return false;
                }
            }

            //everything is fine => replace
            $this->_setTile($selectedTile->x, $selectedTile->y, $newTile);

            $tilesToReplace = array();
            //replace the surrounding tiles accordingly
            foreach ($allSurroundingTiles as $surroundingTile) {
                if ($this->_checkIfTileNeedsToBeChanged($surroundingTile, $newTile->getMaterials()[0]) !== false) {
                    $this->_setTile($surroundingTile->x, $surroundingTile->y, new Tile(999));
                    $tilesToReplace[] = (object)array(
                        'tile' => $tileToChange,
                        'x'    => $surroundingTile->x,
                        'y'    => $surroundingTile->y,
                    );
                }
            }

            /*foreach ($tilesToReplace as $tile) {
                $actualSurroundingMaterialMap = $this->_getSurroundingMaterialMap($tile, $tileToChange->getMaterials()[0]);
                $tileNeeded                   = Tile::getMatchingTileFromSurroundingMaterial($actualSurroundingMaterialMap);
                if ($tileNeeded->getImageId() == 999) {
                    var_dump($actualSurroundingMaterialMap);
                }
                $this->_setTile($tile->x, $tile->y, $tileNeeded);
            }*/

            while(count($tilesToReplace) > 0) {
                $tile = array_pop($tilesToReplace);
                $actualSurroundingMaterialMap = $this->_getSurroundingMaterialMap($tile, $tileToChange->getMaterials()[0]);
                $tileNeeded                   = Tile::getMatchingTileFromSurroundingMaterial($actualSurroundingMaterialMap);
                if ($tileNeeded->getImageId() == 999) {
                    //array_push($tilesToReplace, $tile);
                    var_dump($actualSurroundingMaterialMap);
                }
                    $this->_setTile($tile->x, $tile->y, $tileNeeded);

            }

            return true;
        }

        /**
         * Returns the surrounding tiles for a given tile.
         *
         * @param mixed $tile The tile we want the surroundings for.
         *
         * @return array
         */
        private function _getSurroundingTiles($tile) {
            $surroundingTiles = array();
            $direction        = 0;
            for ($y = $tile->y - 1; $y <= $tile->y + 1; $y++) {
                for ($x = $tile->x - 1; $x <= $tile->x + 1; $x++) {
                    $direction++;
                    //direction 5 = self
                    if (isset($this->_map[$x][$y]) && $direction != 5) {
                        $surroundingTiles[] = (object)array(
                            'tile'      => $this->_map[$x][$y],
                            'x'         => $x,
                            'y'         => $y,
                            'direction' => $direction,
                        );
                    }
                }
            }

            return $surroundingTiles;
        }

        /**
         * Returns the surroundingMaterialMap for the given tile, irgnoring all tiles with more than one material.
         *
         * @param object $tile The tile you want the surroundingMaterialMap for.
         *
         * @return array
         */
        private function _getSurroundingMaterialMap($tile) {
            $surroundingTiles      = $this->_getSurroundingTiles($tile);
            $surroundingTilesIndex = 0;
            $materialMap           = array();
            for ($direction = 1; $direction <= 9; $direction++) {
                if ($surroundingTiles[$surroundingTilesIndex]->direction == $direction) {
                    $actualTile = $surroundingTiles[$surroundingTilesIndex]->tile;
                    if ($actualTile instanceof Tile) {
                        $actualMaterials = $actualTile->getMaterials();
                        if ($actualMaterials[0] != 999) {
                            $materialMap[$direction] = $actualMaterials;
                        }
                    }
                    $surroundingTilesIndex++;
                }
            }

            return $materialMap;
        }

        /**
         * Checks if a tile needs to be changed.
         *
         * @param object  $surroundedTile The tile.
         * @param integer $materialId     The materialId to check against.
         *
         * @return boolean|array
         */
        private function _checkIfTileNeedsToBeChanged($surroundedTile, $materialId) {
            $tile = $surroundedTile->tile;
            if ($tile instanceof Tile) {
                $surroundingTiles = $this->_getSurroundingTiles($surroundedTile);
                foreach ($surroundingTiles as $surroundingTile) {
                    if ($surroundingTile->tile->getMaterials() !== $materialId) {
                        return $surroundingTiles;
                    }
                }
            }

            return false;
        }

        /**
         * Returns a tile and calculates the offset with.
         *
         * @param integer $x The x coord.
         * @param integer $y The y coord.
         *
         * @return Tile
         */
        private function _getTile($x, $y) {
            return $this->_map[$x][$y];
        }

        /**
         * Sets a tile and calculates the offset with.
         *
         * @param integer $x       The x coord.
         * @param integer $y       The y coord.
         * @param Tile    $newTile The new tile.
         */
        private function _setTile($x, $y, $newTile) {
            $this->_map[$x + $this->_offsetX][$y + $this->_offsetY] = $newTile;
        }
    }