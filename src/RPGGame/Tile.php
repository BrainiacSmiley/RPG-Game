<?php
    namespace RPGGame;


    class Tile {
        const MATERIAL_ROCK  = 1;
        const MATERIAL_GRASS = 2;
        const MATERIAL_PAVE  = 3;

        private $_imageId;
        private $_fromMaterial;
        private $_toMaterial;
        private $_position;
        public $x;
        public $y;

        /**
         * Creates a new tile with the given materials and direction.
         *
         * The fromMaterial and toMaterial represents the materials that are present on the tile. From left to right.
         * The position represents the position/orientation of the tile.
         * For examples see TilesOverview.html
         *
         * @param integer $fromMaterial The material the tile comes from.
         * @param integer $toMaterial   The material the tile gos to.
         * @param integer $position     The position/orientation of the tile.
         * @param integer $x            The x position of the tile.
         * @param integer $y            The y position of the tile.
         */
        public function __construct($fromMaterial, $toMaterial = null, $position = 5, $x = -1, $y = -1) {
            $this->_fromMaterial = $fromMaterial;
            $this->_toMaterial   = $toMaterial !== null ? $toMaterial : $fromMaterial;
            $this->_imageId      = $this->_getImageIfForMaterial($this->_fromMaterial, $this->_toMaterial, $position);
            $this->_position     = $position;
            $this->x             = $x;
            $this->y             = $y;
        }

        /**
         * Returns the imageId.
         *
         * @return integer
         */
        public function getImageId() {
            return $this->_imageId;
        }

        /**
         * Returns the materials.
         *
         * @return array
         */
        public function getMaterials() {
            if ($this->_fromMaterial == $this->_toMaterial) {
                return array($this->_fromMaterial);
            }
            else {
                return array($this->_fromMaterial, $this->_toMaterial);
            }
        }

        /**
         * Checks if a tile can be replaced by a given materialId.
         *
         * @param integer $materialId The material id you want to replace with.
         *
         * @return boolean
         */
        public function canBeReplacedBy($materialId) {
            //pave not on grass
            //grass not on pave
            if (
                ($materialId == self::MATERIAL_PAVE && in_array(self::MATERIAL_GRASS, $this->getMaterials())) ||
                ($materialId == self::MATERIAL_GRASS && in_array(self::MATERIAL_PAVE, $this->getMaterials()))
            ) {
                return false;
            }

            return true;
        }

        /**
         * Returns the tile for the given surrounding material.
         *
         * @param array $surroundingMaterial The surroundingMaterial.
         *
         * @return Tile
         */
        public static function getMatchingTileFromSurroundingMaterial(array $surroundingMaterial) {
            //check vertical
            $matchingTile = new Tile(999);
            if (self::_checkArray($surroundingMaterial, 2) != -1 && self::_checkArray($surroundingMaterial, 8) != -1 && self::_checkArray($surroundingMaterial, 2) != self::_checkArray($surroundingMaterial, 8)) {
                $matchingTile = self::_checkDiagonal($surroundingMaterial);
                if ($matchingTile === false) {
                    $matchingTile = new Tile($surroundingMaterial[2][0], $surroundingMaterial[8][0], 2);
                }
            }
            else if (self::_checkArray($surroundingMaterial, 4) != -1 && self::_checkArray($surroundingMaterial, 6) != -1 && self::_checkArray($surroundingMaterial, 4) != self::_checkArray($surroundingMaterial, 6)) {
                $matchingTile = self::_checkDiagonal($surroundingMaterial);
                if ($matchingTile === false) {
                    $matchingTile = new Tile($surroundingMaterial[4][0], $surroundingMaterial[6][0], 4);
                }
            }
            else if (self::_checkArray($surroundingMaterial, 2) != -1 && self::_checkArray($surroundingMaterial, 2) == self::_checkArray($surroundingMaterial, 8)) {
                $matchingTile = self::_checkDiagonal($surroundingMaterial);
                if ($matchingTile === false) {
                    $matchingTile = new Tile($surroundingMaterial[2][0]);
                }
            }
            else if (self::_checkArray($surroundingMaterial, 4) != -1 && self::_checkArray($surroundingMaterial, 4) == self::_checkArray($surroundingMaterial, 6)) {
                $matchingTile = self::_checkDiagonal($surroundingMaterial);
                if ($matchingTile === false) {
                    $matchingTile = new Tile($surroundingMaterial[4][0]);
                }
            }
            else {
                $diagonalCheck = self::_checkDiagonal($surroundingMaterial);
                if ($diagonalCheck !== false) {
                    $matchingTile = $diagonalCheck;
                }
            }

            /*if (
                (self::_checkArray($surroundingMaterial, 2) != -1 && self::_checkArray($surroundingMaterial, 4) != -1 && self::_checkArray($surroundingMaterial, 6) != -1 && self::_checkArray($surroundingMaterial, 8) != -1) &&
                (self::_checkArray($surroundingMaterial, 2) == self::_checkArray($surroundingMaterial, 4) && self::_checkArray($surroundingMaterial, 4) == self::_checkArray($surroundingMaterial, 6) && self::_checkArray($surroundingMaterial, 6) == self::_checkArray($surroundingMaterial, 8))
            ) {
                $matchingTile = new Tile($surroundingMaterial[2][0]);
            }*/

            return $matchingTile;
        }

        /**
         * Helper function for getMatchingTileFromSurroundingMaterial.
         *
         * @param array $surroundingMaterial The surroundingMaterial.
         *
         * @return boolean|Tile
         */
        private static function _checkDiagonal(array $surroundingMaterial) {
            $diagonalTile = false;
            if ((self::_checkArray($surroundingMaterial, 1) != -1 && self::_checkArray($surroundingMaterial, 3) != -1 && self::_checkArray($surroundingMaterial, 7) != -1 && self::_checkArray($surroundingMaterial, 9) != -1) &&
                (count($surroundingMaterial[1]) == 1 && count($surroundingMaterial[3]) == 1 && count($surroundingMaterial[7]) == 1 && count($surroundingMaterial[9]) == 1)
            ) {
                if (self::_checkArray($surroundingMaterial, 1) != self::_checkArray($surroundingMaterial, 3) && self::_checkArray($surroundingMaterial, 3) == self::_checkArray($surroundingMaterial, 7) && self::_checkArray($surroundingMaterial, 7) == self::_checkArray($surroundingMaterial, 9)) {
                    $diagonalTile = new Tile($surroundingMaterial[3][0], $surroundingMaterial[1][0], 9);
                }
                else if (self::_checkArray($surroundingMaterial, 3) != self::_checkArray($surroundingMaterial, 1) && self::_checkArray($surroundingMaterial, 1) == self::_checkArray($surroundingMaterial, 7) && self::_checkArray($surroundingMaterial, 7) == self::_checkArray($surroundingMaterial, 9)) {
                    $diagonalTile = new Tile($surroundingMaterial[1][0], $surroundingMaterial[3][0], 7);
                }
                else if (self::_checkArray($surroundingMaterial, 7) != self::_checkArray($surroundingMaterial, 1) && self::_checkArray($surroundingMaterial, 1) == self::_checkArray($surroundingMaterial, 3) && self::_checkArray($surroundingMaterial, 3) == self::_checkArray($surroundingMaterial, 9)) {
                    $diagonalTile = new Tile($surroundingMaterial[1][0], $surroundingMaterial[7][0], 3);
                }
                else if (self::_checkArray($surroundingMaterial, 9) != self::_checkArray($surroundingMaterial, 1) && self::_checkArray($surroundingMaterial, 1) == self::_checkArray($surroundingMaterial, 3) && self::_checkArray($surroundingMaterial, 3) == self::_checkArray($surroundingMaterial, 7)) {
                    $diagonalTile = new Tile($surroundingMaterial[1][0], $surroundingMaterial[9][0], 1);
                }
            }

            return $diagonalTile;
        }

        /**
         * Checks an given index in an array and returns the value at the index or -1.
         *
         * @param array   $materialArray The array to check.
         * @param integer $position      The index to check.
         *
         * @return integer
         */
        private static function _checkArray(array $materialArray, $position) {
            if (isset($materialArray[$position])) {
                if (count($materialArray[$position]) == 2) {
                    return $materialArray[$position][1];
                }
                else {
                    return $materialArray[$position][0];
                }
            }
            else {
                return -1;
            }
        }

        /**
         * Returns the imageId for a given Material.
         *
         * @param integer $fromMaterial The material the tile comes from.
         * @param integer $toMaterial   The material the tile gos to.
         * @param integer $position     The direction/position of the tile.
         *
         * @return integer
         */
        private
        function _getImageIfForMaterial($fromMaterial, $toMaterial, $position) {
            $possibleMaterials = array(self::MATERIAL_ROCK, self::MATERIAL_GRASS, self::MATERIAL_PAVE);
            if (!in_array($fromMaterial, $possibleMaterials) && !in_array($toMaterial, $possibleMaterials)) {
                return 999;
            }
            else if ($position != 5 && $fromMaterial != $toMaterial) {
                return (int)$fromMaterial . $toMaterial . $position;
            }
            else if ($fromMaterial == $toMaterial) {
                return (int)$fromMaterial;
            }
        }
    }

    ?>