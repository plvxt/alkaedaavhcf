<?php

namespace alkaedaav\block\redstone;

use alkaedaav\Factions;

use pocketmine\Player;

use pocketmine\block\{Block, Glass, Transparent, FenceGate, Door, BlockToolType};
use pocketmine\item\{Item, TieredTool};
use pocketmine\math\Vector3;
use pocketmine\math\AxisAlignedBB;
use pocketmine\entity\Entity;

use pocketmine\level\sound\DoorSound;

class PressurePlate extends Transparent {

    /**
     * @return bool
     */
    public function isSolid() : bool {
        return false;
    }
    
    /**
     * @return bool
     */
    public function hasEntityCollision() : bool {
        return true;
    }

	/**
     * @return bool
     */
    public function canPassThrough() : bool {
        return true;
    }

    /**
     * @return float
     */
    public function getHardness() : float {
        return 0.5;
    }

    /**
     * @return Int
     */
    public function getVariantBitmask() : Int {
        return 0;
    }

    /**
     * @return Int
     */
    public function getToolType() : Int {
        return BlockToolType::TYPE_PICKAXE;
    }

    /**
     * @return Int
     */
    public function getToolHarvestLevel() : Int {
        return TieredTool::TIER_WOODEN;
    }

    /**
	 * @param Item $item
	 * @param Block $blockReplace
	 * @param Block $blockClicked
	 * @param Int $face
	 * @param Vector3 $clickVector
	 * @param Player $player
	 * @return bool
	 */
    public function place(Item $item, Block $blockReplace, Block $blockClicked, Int $face, Vector3 $clickVector,Player $player = null) : bool {
		if(!$blockClicked->isSolid()||$blockClicked instanceof Glass){
			return false;
        }
        $this->getLevel()->setBlock($blockReplace, $this, true, true);
		return true;
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function onEntityCollide(Entity $entity) : void {
        if($entity instanceof Player && Factions::getRegionName($entity) !== Factions::getFaction($entity->getName())) return;
        
        $damage = $this->nearbyEntities();
        if($this->getDamage() !== $damage){
            foreach($this->getAllSides() as $block){
                if($block instanceof Door){
                	if(($block->getDamage() & 0x08) === 0x08){
                		$down = $block->getSide(Vector3::SIDE_DOWN);
						if($down->getId() === $block->getId()){
							$meta = $down->getDamage() ^ 0x04;
							$this->getlevel()->setBlock($block, $block, true, true);
						}
					}else{
						$block->meta ^= 0x04;
						$this->getlevel()->setBlock($block, $block, true, true);
                    }
                    $this->getLevel()->addSound(new DoorSound($block));
                }
            }
            $this->setDamage($damage);
            $this->getlevel()->setBlock($this, $this, true, true);
        }
        $this->getLevel()->scheduleDelayedBlockUpdate($this, 20);
    }

    /**
     * @return void
     */
    public function onScheduledUpdate() : void {
        $damage = $this->nearbyEntities();
        if($this->getDamage() !== $damage){
            foreach($this->getAllSides() as $block){
                if($block instanceof Door){
                	if(($block->getDamage() & 0x08) === 0x08){
                		$down = $block->getSide(Vector3::SIDE_DOWN);
						if($down->getId() === $block->getId()){
							$meta = $down->getDamage() ^ 0x04;
							$this->getlevel()->setBlock($block, $block, true, true);
						}
					}else{
						$block->meta ^= 0x04;
						$this->getlevel()->setBlock($block, $block, true, true);
                    }
                    $this->getLevel()->addSound(new DoorSound($block));
                }
            }
            $this->setDamage($damage);
            $this->getlevel()->setBlock($this, $this, true, true);
        }
        if($damage > 0){
            $this->getLevel()->scheduleDelayedBlockUpdate($this, 20);
        }
    }

    /**
     * @return Int
     */
    protected function nearbyEntities() : Int {
        $value = count($this->getLevel()->getNearbyEntities($this->box()));
        return $value > 0 ? 1 : 0;
    }

    /**
     * @return AxisAlignedBB
     */
    protected function box() : AxisAlignedBB {
        return new AxisAlignedBB(
            $this->x + 0.0625,
            $this->y,
            $this->z + 0.0625,
            $this->x + 0.9375,
            $this->y + 0.0625,
            $this->z + 0.9375,
        );
    }
    
    /**
     * @return void
     */
    public function onNearbyBlockChange() : void {
        $under = $this->getSide(Vector3::SIDE_DOWN);
        if ($under->isSolid() && !$under->isTransparent()) {
            return;
        }
        $this->getLevel()->useBreakOn($this);
    }
}

?>