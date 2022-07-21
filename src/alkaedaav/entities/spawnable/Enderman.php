<?php

namespace alkaedaav\entities\spawnable;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\sound\EndermanTeleportSound;

class Enderman extends Monster {
	
	const NETWORK_ID = self::ENDERMAN;
	
	/** @var float */
	public $width = 0.6, $height = 3.5;
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return "Enderman";
	}
	
	/**
	 * @return Array
	 */
	public function getDrops() : Array {
		return [
			Item::get(Item::ENDER_PEARL, 0, mt_rand(0, 3)),
        ];
    }
    
    /**
     * @return Int
     */
    public function getXpDropAmount() : Int {
    	return mt_rand(3, 10);
    }
    
    /**
	 * @param EntityDamageEvent $source
	 * @return void
	 */
    public function attack(EntityDamageEvent $source) : void {
    	parent::attack($source);
    	$this->getLevel()->addSound(new EndermanTeleportSound($this));
    	$this->teleport($this->add(rand(-5, 5), $this->getLevel()->getHighestBlockAt($this->getX(), $this->getZ()), rand(-5, 5)));
    	$this->getLevel()->addSound(new EndermanTeleportSound($this));
    }
}

?>