<?php

namespace alkaedaav\block;

use alkaedaav\block\redstone\{
    PressurePlate,
};
use pocketmine\block\{Block, BlockFactory};

class Blocks {

    /**
     * @return void
     */
    public static function init() : void {
        BlockFactory::registerBlock(new MonsterSpawner(), true);
        BlockFactory::registerBlock(new EndPortalFrame(), true);
        
        BlockFactory::registerBlock(new CustomChest(), true);

		BlockFactory::registerBlock(new PressurePlate(Block::STONE_PRESSURE_PLATE, 0, "Stone Pressure Plate"), true);
		BlockFactory::registerBlock(new PressurePlate(Block::HEAVY_WEIGHTED_PRESSURE_PLATE, 0, "Heavy Weighted Pressure Plate"), true);
		BlockFactory::registerBlock(new PressurePlate(Block::LIGHT_WEIGHTED_PRESSURE_PLATE, 0, "Light Weighted Pressure Plate"), true);
		BlockFactory::registerBlock(new PressurePlate(Block::WOODEN_PRESSURE_PLATE, 0, "Wooden Pressure Plate"), true);
    }
}

?>