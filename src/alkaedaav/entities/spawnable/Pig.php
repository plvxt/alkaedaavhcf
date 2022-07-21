<?php

namespace alkaedaav\entities\spawnable;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Pig extends Monster {
	
	const NETWORK_ID = self::PIG;
	
	/** @var float */
	public $width = 0.8, $height = 1.8;
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return "Pig";
	}
	
	/**
	 * @return Array
	 */
	public function getDrops() : Array {
		return [
			Item::get(Item::RAW_PORKCHOP, 0, mt_rand(1, 3)),
            Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
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