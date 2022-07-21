<?php

namespace alkaedaav\entities\spawnable;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Creeper extends Monster {
	
	const NETWORK_ID = self::CREEPER;
	
	/** @var float */
	public $width = 0.9, $height = 1.5;
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return "Creeper";
	}
	
	/**
	 * @return Array
	 */
	public function getDrops() : Array {
		return [
			Item::get(Item::GUNPOWDER, 0, mt_rand(1, 3)),
        ];
    }
    
    /**
     * @return Int
     */
    public function getXpDropAmount() : Int {
    	return mt_rand(3, 10);
    }
}

?>