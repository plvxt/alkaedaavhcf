<?php

namespace alkaedaav\item\netherite;

use alkaedaav\item\Items;

use pocketmine\block\{Block, BlockToolType};
use pocketmine\item\TieredTool;
use pocketmine\entity\Entity;

class Sword extends TieredTool {

    /**
     * Sword Constructor.
     * @param Int $meta
     */
    public function __construct(Int $meta = 0){
        parent::__construct(Items::NETHERITE_SWORD, $meta, "Netherite Sword", 5);
    }

    /**
     * @return Int
     */
    public function getBlockToolType() : Int {
        return BlockToolType::TYPE_SWORD;
    }

    /**
     * @return Int
     */
    public function getAttackPoints() : Int {
        return 9;
    }

    /**
     * @return Int
     */
    public function getBlockToolHarvestLevel() : Int {
        return 1;
    }

    /**
     * @param Block $block
     * @return float
     */
    public function getMiningEfficiency(Block $block) : float {
        return parent::getMiningEfficiency($block) * 1.5;
    }

    /**
     * @return float
     */
    public function getBaseMiningEfficiency() : float {
        return 9;
    }

    /**
     * @param Block $block
     * @return bool
     */
    public function onDestroyBlock(Block $block) : bool {
        if($block->getHardness() > 0){
            return $this->applyDamage(2);
        }
        return false;
    }

    /**
     * @return Int
     */
    public function getMaxDurability() : Int {
        return 407;
    }

    /**
     * @param Entity $victim
     * @return bool
     */
    public function onAttackEntity(Entity $victim) : bool {
        return $this->applyDamage(1);
    }
}

?>