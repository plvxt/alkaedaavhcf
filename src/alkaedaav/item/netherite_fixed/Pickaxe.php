<?php

namespace alkaedaav\item\netherite;

use alkaedaav\item\Items;

use pocketmine\block\{Block, BlockToolType};
use pocketmine\entity\Entity;

class Pickaxe extends \pocketmine\item\Pickaxe {

    /**
     * Pickaxe Constructor.
     * @param Int $meta
     */
    public function __construct(int $meta = 0){
        parent::__construct(Items::NETHERITE_PICKAXE, $meta, "Netherite Pickaxe", 5);
    }

    /**
     * @return Int
     */
    public function getBlockToolType() : Int {
        return BlockToolType::TYPE_PICKAXE;
    }

    /**
     * @return Int
     */
    public function getBlockToolHarvestLevel() : Int {
        return 5;
    }

    /**
     * @return Int
     */
    public function getMaxDurability() : Int {
        return 407;
    }

    /**
     * @param Block $block
     * @return bool
     */
    public function onDestroyBlock(Block $block) : bool {
        if($block->getHardness() > 0) {
            return $this->applyDamage(1);
        }
        return false;
    }

    /**
     * @return Int
     */
    public function getAttackPoints() : Int {
        return self::getBaseDamageFromTier($this->tier) - 2;
    }

    /**
     * @param Entity $victim
     * @return bool
     */
    public function onAttackEntity(Entity $victim) : bool {
        return $this->applyDamage(2);
    }
}